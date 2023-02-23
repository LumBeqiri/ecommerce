<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\CartService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveCookieCartToDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected mixed $items;

    protected User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(mixed $items, User $user)
    {
        $this->items = $items;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CartService::saveItemsToCart($this->items, $this->user);
    }
}
