<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;

class UsersACLRepository implements ACLRepository
{
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID()
    {
        return \Auth::id();
    }

    /**
     * Get ACL rules list for user
     *
     * @return array
     */
    public function getRules(): array
    {
        if (\Auth::id() === 1) {
            return [
                ['disk' => 'local', 'path' => '*', 'access' => 2],
            ];
        }
        
        return [
            ['disk' => 'local', 'path' => '/', 'access' => 0],                                  // main folder - read
            ['disk' => 'local', 'path' => 'users', 'access' => 0],                              // only read
            ['disk' => 'local', 'path' => 'users/'. \Auth::user()->creatorId(), 'access' => 1],        // only read
            ['disk' => 'local', 'path' => 'users/'. \Auth::user()->creatorId() .'/*', 'access' => 2],  // read and write
        ];
    }
}