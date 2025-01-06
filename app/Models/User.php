<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relacionamento: Um usuário pode ter vários statuses.
     */
    public function statuses()
    {
        return $this->hasMany(Status::class, 'user_id');
    }

    /**
     * Relacionamento: Um usuário pode ter várias categorias.
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'user_id');
    }

    /**
     * Relacionamento: Um usuário pode ter várias tasks.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    /**
     * Relacionamento: Um usuário pode ter vários logs.
     */
    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }


    /**
     * Criar categorias padrão para o usuário.
     */
    public function createDefaultCategories()
    {
        $this->categories()->createMany([
            ['name' => 'Trabalho', 'color' => '#FF5733'],
            ['name' => 'Casa', 'color' => '#33FF57'],
            ['name' => 'Estudos', 'color' => '#3357FF'],
        ]);
    }

    /**
     * Criar status padrão para o usuário.
     */
    public function createDefaultStatuses()
    {
        $this->statuses()->createMany([
            ['name' => 'Em Aguardo', 'description' => 'Aguardando início', 'color' => '#808080', 'order' => 1, 'is_finalized' => false],
            ['name' => 'Em Progresso', 'description' => 'Em andamento', 'color' => '#0000FF', 'order' => 2, 'is_finalized' => false],
            ['name' => 'Cancelado', 'description' => 'Cancelado pelo usuário', 'color' => '#FF0000', 'order' => 3, 'is_finalized' => true],
            ['name' => 'Finalizado', 'description' => 'Concluído com sucesso', 'color' => '#00FF00', 'order' => 4, 'is_finalized' => true],
        ]);
    }

}
