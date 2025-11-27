<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    // LISTAR ARCHIVOS DE BACKUP
    public function lista()
    {
        $files = Storage::files('backups');

        return response()->json($files);
    }

    public function generar()
    {
        try {
            $database = env('DB_DATABASE');

            $filename = "backup_" . date('Y-m-d_H-i-s') . ".sql";
            $path = storage_path("app/backups/$filename");
            
            Storage::makeDirectory('backups');

            $sql = "";

            // Listar tablas
            $tables = DB::select('SHOW TABLES');
            $tables = array_map('current', $tables);

            foreach ($tables as $table) {

                // Estructura
                $create = DB::select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};
                $sql .= "\n\n-- ESTRUCTURA DE $table --\n\n$create;\n\n";

                // Datos
                $rows = DB::table($table)->get();

                foreach ($rows as $row) {
                    $values = array_map(function ($value) {
                        return is_null($value)
                            ? "NULL"
                            : "'" . addslashes($value) . "'";
                    }, (array) $row);

                    // FIX: aquí estaba tu error
                    $sql .= "INSERT INTO `$table` VALUES(" . implode(",", $values) . ");\n";
                }

                $sql .= "\n\n";
            }

            file_put_contents($path, $sql);

            return response()->json([
                'success' => true,
                'message' => "Backup generado: $filename"
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }




    // RESTAURAR UN BACKUP (importar SQL)
    public function restaurar(Request $request)
    {
    try {
    $request->validate([
    'archivo' => 'required|string'
    ]);

        $archivo = $request->archivo;
        $path = storage_path("app/" . $archivo);

        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'error' => "El archivo no existe en $archivo"
            ], 404);
        }

        $sql = file_get_contents($path);
        if (!$sql || strlen($sql) < 10) {
            return response()->json([
                'success' => false,
                'error' => "El archivo SQL está vacío o corrupto."
            ], 500);
        }

        // Detectar nombres de tablas a partir del SQL
        preg_match_all('/CREATE TABLE `([^`]*)`/i', $sql, $matches);
        $tablasBackup = $matches[1] ?? [];

        // Desactivar llaves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Eliminar solo las tablas que están en el backup
        foreach ($tablasBackup as $tabla) {
            DB::statement("DROP TABLE IF EXISTS `$tabla`");
        }

        // Restaurar SQL completo
        DB::unprepared($sql);

        // Reactivar llaves
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response()->json([
            'success' => true,
            'message' => "Tablas del backup restauradas correctamente sin afectar otras tablas."
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }

    }


    // ELIMINAR BACKUP
    public function eliminar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|string'
        ]);

        Storage::delete($request->archivo);

        return back()->with("success", "Backup eliminado.");
    }

    public function descargar($archivo)
    {
        $path = storage_path("app/backups/$archivo");

        if (!file_exists($path)) {
            abort(404, "El backup no existe.");
        }

        return response()->download($path);
    }



}
