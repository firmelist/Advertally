<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'disk', 'path', 'mime_type', 'size', 'alt', 'uploaded_by'];

    protected static function booted(): void
    {
        static::deleted(fn (self $media) => Storage::disk($media->disk)->delete($media->path));
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
