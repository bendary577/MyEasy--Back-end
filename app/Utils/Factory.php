<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface Factory {
    public function create(Model $model, Request $request): string;
    public function getAll(Model $model): string;
    public function get($id, Model $model): string;
    public function update($id, Model $model, Request $request): string;
    public function delete($id, Model $model): string;
}

