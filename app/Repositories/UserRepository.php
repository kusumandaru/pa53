<?php

namespace App\Repositories;

use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nik',
        'email',
        'nama_rekening',
        'rekening',
        'bank',
        'cabang',
        'name',
        'role',
        'department',
        'position',
        'remember_token',
        'image',
        'created_at',
        'updated_at',
        'pjsvp'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }
}
