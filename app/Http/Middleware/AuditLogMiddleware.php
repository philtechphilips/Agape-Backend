<?php

namespace App\Http\Middleware;

use App\Models\Admin\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log successful mutative actions (POST, PATCH, PUT, DELETE)
        if ($response->isSuccessful() && in_array($request->method(), ['POST', 'PATCH', 'PUT', 'DELETE'])) {
            $this->logAction($request);
        }

        return $response;
    }

    protected function logAction(Request $request)
    {
        $user = Auth::user();
        if (!$user) return;

        $action = $request->method() . ' ' . $request->path();
        $description = $this->generateDescription($request, $user);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'payload' => $this->sanitizePayload($request->all()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    protected function generateDescription(Request $request, $user)
    {
        $method = $request->method();
        $path = $request->path();
        $data = $request->all();

        // Basic human-readable translation logic
        $target = 'resource';
        if (str_contains($path, 'student')) $target = 'student';
        elseif (str_contains($path, 'parent')) $target = 'parent';
        elseif (str_contains($path, 'staff')) $target = 'staff';
        elseif (str_contains($path, 'class')) $target = 'class';
        elseif (str_contains($path, 'subject')) $target = 'subject';
        elseif (str_contains($path, 'session')) $target = 'academic session';
        elseif (str_contains($path, 'attendance')) $target = 'attendance records';
        elseif (str_contains($path, 'assessment')) $target = 'assessment scores';
        elseif (str_contains($path, 'result')) $target = 'exam results';

        $verb = 'performed an action on';
        if ($method === 'POST') $verb = 'created a new';
        elseif ($method === 'PATCH' || $method === 'PUT') $verb = 'updated';
        elseif ($method === 'DELETE') $verb = 'deleted';

        $name = '';
        if (isset($data['firstname']) || isset($data['surname'])) {
            $name = ' (' . ($data['surname'] ?? '') . ' ' . ($data['firstname'] ?? '') . ')';
        } elseif (isset($data['classname'])) {
            $name = ' (' . $data['classname'] . ')';
        } elseif (isset($data['subject'])) {
            $name = ' (' . $data['subject'] . ')';
        }

        return "{$user->name} {$verb} {$target}{$name}.";
    }

    protected function sanitizePayload($data)
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'image'];
        return collect($data)->except($sensitiveFields)->toArray();
    }
}
