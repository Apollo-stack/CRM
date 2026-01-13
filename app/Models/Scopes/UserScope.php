<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UserScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Se for Gerente ou Admin, VÊ TUDO (não aplica o filtro)
        if (auth()->check() && auth()->user()->isManager()) {
            return;
        }

        if (auth()->check()) {
            $builder->where($model->getTable() . '.user_id', auth()->id());
        }
    }
}
