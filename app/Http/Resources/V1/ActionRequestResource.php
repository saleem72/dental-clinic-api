<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**

            $table->foreignId('created_by_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to_id')->constrained('users')->cascadeOnDelete()->nullable();

            // Optional patient treatment context
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('treatment_course_id')->nullable()->constrained('treatment_courses')->nullOnDelete();
            $table->foreignId('treatment_session_id')->nullable()->constrained('treatment_sessions')->nullOnDelete();

            // Enums
            $table->string('type');   // via Enum
            $table->string('status'); // via Enum

            // Extra data (like new date for rescheduling, reason, notes)
            $table->json('payload')->nullable();

            // Doctor’s response/notes
            $table->text('doctor_note')->nullable();

            $table->timestamp('resolved_at')->nullable();

         */
        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'creator' => $this->whenLoaded('creator', new UserMiniResource($this->creator)),
            'assignee'=> $this->whenLoaded('assignee', new UserMiniResource($this->assignee)),
        ];
    }
}
