<?php

namespace App\Repositories\Interfaces;

interface TaskRepositoryInterface
{
    // public function all();
    // public function findById($id);
    // public function create(array $data);
    // public function update($id, array $data);
    // public function delete($id);
    // public function findByUserId($userId);
    // public function updateOrder(array $data);
    public function getAllForUser($userId, $filters = []);
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function updateOrder(array $orderedTasks);
}