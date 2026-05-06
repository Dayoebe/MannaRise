<?php

namespace App\Support;

class Toast
{
    public static function status(object $component, string $message): void
    {
        session()->flash('status', $message);

        if (method_exists($component, 'dispatch')) {
            $component->dispatch('mannarise-toast', message: $message);
        }
    }
}
