<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\JsonResponse;

class DateTimeController extends Controller
{
    /**
     * Obtiene la fecha y hora actual del servidor
     */
    public function getCurrentDateTime(): JsonResponse
    {
        try {
            $dateTimeInfo = ReportService::getDateInfoForFrontend();
            
            return response()->json([
                'success' => true,
                'data' => $dateTimeInfo,
                'message' => 'Fecha y hora obtenidas correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener fecha y hora',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene la zona horaria actual del sistema
     */
    public function getTimezone(): JsonResponse
    {
        try {
            $timezone = ReportService::getSystemTimezone();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'timezone' => $timezone,
                    'current_datetime' => ReportService::getDateInfoForFrontend()
                ],
                'message' => 'Zona horaria obtenida correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener zona horaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Establece la zona horaria del sistema
     */
    public function setTimezone(): JsonResponse
    {
        try {
            $request->validate([
                'timezone' => 'required|string|timezone'
            ]);

            $timezone = request('timezone');
            ReportService::setSystemTimezone($timezone);

            return response()->json([
                'success' => true,
                'data' => [
                    'timezone' => $timezone,
                    'current_datetime' => ReportService::getDateInfoForFrontend()
                ],
                'message' => 'Zona horaria actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar zona horaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene la lista de zonas horarias disponibles
     */
    public function getAvailableTimezones(): JsonResponse
    {
        try {
            $timezones = [
                'America/Los_Angeles' => 'PST/PDT - Pacific Time (Los Ángeles)',
                'America/New_York' => 'EST/EDT - Eastern Time (Nueva York)',
                'America/Chicago' => 'CST/CDT - Central Time (Chicago)',
                'America/Denver' => 'MST/MDT - Mountain Time (Denver)',
                'America/Mexico_City' => 'CST/CDT - Mexico City Time',
                'America/Bogota' => 'COT - Colombia Time',
                'America/Caracas' => 'VET - Venezuela Time',
                'America/Santiago' => 'CLT/CLST - Chile Time',
                'America/Buenos_Aires' => 'ART - Argentina Time',
                'America/Lima' => 'PET - Peru Time',
                'Europe/London' => 'GMT/BST - London Time',
                'Europe/Paris' => 'CET/CEST - Paris Time',
                'Europe/Madrid' => 'CET/CEST - Madrid Time',
                'Asia/Tokyo' => 'JST - Japan Time',
                'Asia/Shanghai' => 'CST - China Time',
                'UTC' => 'UTC - Coordinated Universal Time'
            ];

            return response()->json([
                'success' => true,
                'data' => $timezones,
                'message' => 'Zonas horarias obtenidas correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener zonas horarias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea un reporte desde el backend
     */
    public function createReport(): JsonResponse
    {
        try {
            $reportName = request('name', 'Reporte Generado');
            $content = request('content', 'Contenido del reporte');
            $metadata = request('metadata', []);

            $filepath = ReportService::createReport($reportName, $content, $metadata);
            ReportService::updateDayIndex($reportName, $metadata);

            return response()->json([
                'success' => true,
                'data' => [
                    'filepath' => $filepath,
                    'date_info' => ReportService::getDateInfoForFrontend()
                ],
                'message' => 'Reporte creado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear reporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
