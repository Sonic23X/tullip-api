<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use App\User;

class Users extends Controller
{
    function GetUsers($pageSize, $currentPage, $search = null)
    {
      $users = null;

      if ($search == null)
      {
        if ($currentPage == "1")
        {
          $users = User::limit($pageSize)
                       ->get();

          $totalItem = User::limit($pageSize)
                           ->count();
        }
        else
        {
          $users = User::offset($pageSize * ($currentPage - 1))
                       ->limit($pageSize)
                       ->get();

          $totalItem = User::offset($pageSize * ($currentPage - 1))
                       ->limit($pageSize)
                       ->count();
        }

        $response = array
        (
           'status' => true,
           'totalItem' => $totalItem,
           'totalPage' => round(User::count() / $pageSize),
           'pageSize' => $pageSize,
           'currentPage' => $currentPage,
           'data' => $users
        );
      }
      else
      {
        if ($currentPage == "1")
        {
            $users = User::where('name', 'like', '%'.$search.'%')
                         ->limit($pageSize)
                         ->get();
        }
        else
        {
            $users = User::where('name', 'like', '%'.$search.'%')
                         ->offset($pageSize * ($currentPage - 1))
                         ->limit($pageSize)
                         ->get();
        }

        $response = array
        (
           'status' => true,
           'totalItem' => User::where('name', 'like', '%'.$search.'%')->count(),
           'totalPage' => User::where('name', 'like', '%'.$search.'%')->count(),
           'pageSize' => $pageSize,
           'currentPage' => $currentPage,
           'data' => $users
        );
      }

      return $response;
    }
}
