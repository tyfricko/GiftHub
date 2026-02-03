<?php

namespace App\Jobs;

use App\Models\Wishlist;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadWishlistImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $wishlistId;

    protected $imageUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $wishlistId, string $imageUrl)
    {
        $this->wishlistId = $wishlistId;
        $this->imageUrl = $imageUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $wishlist = Wishlist::find($this->wishlistId);

            if (! $wishlist) {
                // Log or handle the case where the wishlist is not found
                return;
            }

            $contents = file_get_contents($this->imageUrl);
            if ($contents === false) {
                throw new Exception("Failed to download image from URL: {$this->imageUrl}");
            }

            $extension = pathinfo($this->imageUrl, PATHINFO_EXTENSION);
            $filename = 'wishlist_images/'.Str::uuid().'.'.$extension;

            Storage::disk('public')->put($filename, $contents);

            $wishlist->image_url = Storage::disk('public')->url($filename);
            $wishlist->save();

        } catch (Exception $e) {
            // Log the error, e.g., to Laravel's default log
            \Log::error("Error downloading image for wishlist ID {$this->wishlistId}: ".$e->getMessage());
        }
    }
}
