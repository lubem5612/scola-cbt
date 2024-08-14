<?php


namespace Transave\ScolaCbt\Actions\User;


use Transave\ScolaCbt\Helpers\SearchHelper;

class SearchUsers
{
    use SearchHelper;

    private function searchTerms()
    {
        $role = request()->query('role');
        $search = $this->searchParam;
        $this->queryBuilder->where('role', '!=', 'admin');
        if (isset($role)) {
            $this->queryBuilder->where('role', $role);
        }else {
            $this->queryBuilder->where('role', '!=', 'admin');
        }
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('telephone', 'like', "%$search%")
                ->orWhereHas('student', function ($query1) use ($search) {
                    $query1->where('phone', 'like', "%$search%")
                        ->orWhere('registration_number', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('department_id', $search)
                        ->orWhereHas('department', function ($query4) use ($search) {
                            $query4->where('name', 'like', "%$search%");
                        });
                })
                ->orWhereHas('manager', function ($query2) use ($search) {
                    $query2->where('phone', 'like', "%$search%");
                })
                ->orWhereHas('examiner', function ($query3) use ($search) {
                    $query3->where('phone', 'like', "%$search%")
                        ->orWhere('department_id', $search)
                        ->orWhereHas('department', function ($query5) use ($search) {
                            $query5->where('name', 'like', "%$search%");
                        });
                })
                ->orWhereHas('staff', function ($query6) use ($search) {
                    $query6->where('phone', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('department_id', $search)
                        ->orWhereHas('department', function ($query5) use ($search) {
                            $query5->where('name', 'like', "%$search%");
                        });
                });
        });

        return $this;
    }
}