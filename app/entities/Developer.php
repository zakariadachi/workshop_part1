<?php

require_once "/TeamMember.php";

class Developer extends TeamMember
{
        function canCreateProject():bool
        {
            return false;
        }
        function canAssignTask():bool
        {
            return false;
        }
        function getRolePermission()
        {
            return ['work_on_tasks'];
        }

}