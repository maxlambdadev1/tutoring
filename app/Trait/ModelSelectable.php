<?php

namespace App\Trait;

trait ModelSelectable
{
    public static function getInstance($tutorId, $parentId, $childId, $type = true)
    {
        if ($type) return self::where(['tutor_id' => $tutorId, 'parent_id' => $parentId, 'child_id' => $childId])->first();
        else return self::where(['tutor_id' => $tutorId, 'parent_id' => $parentId, 'child_id' => $childId])->get();        
    }
}
