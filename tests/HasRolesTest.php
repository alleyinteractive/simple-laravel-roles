<?php

namespace Alley\SimpleRoles\Tests;

class HasRolesTest extends OrchestraTestCase
{
    /**
     * @test
     */
    public function a_user_can_be_assigned_a_role()
    {
        $this->setupDatabase();
        $user = $this->createUser();
        $role = 'admin';
        $this->assertFalse($user->hasRole($role));
        $user->addRole($role);
        $this->assertTrue($user->hasRole($role));
    }

    /**
     * @test
     */
    public function roles_have_capabilities()
    {
        $this->setupDatabase();
        $user = $this->createUser();
        $this->assertFalse($user->hasCapability('edit_posts'));
        $user->addRole('content_editor');
        $this->assertTrue($user->hasCapability('edit_posts'));
        $this->assertFalse($user->hasCapability('edit_videos'));
    }

    /**
     * @test
     */
    public function capabilities_work_with_gate_checks()
    {
        $this->setupDatabase();
        $user = $this->createUser();
        $this->assertFalse($user->can('edit_posts'));
        $user->addRole('content_editor');
        $this->assertTrue($user->can('edit_posts'));
        $this->assertFalse($user->can('edit_videos'));
    }

    /**
     * @test
     */
    public function users_can_have_multiple_roles()
    {
        $this->setupDatabase();
        $user = $this->createUser();
        $this->assertFalse($user->hasCapability('edit_posts'));
        $this->assertFalse($user->hasCapability('edit_videos'));
        $user->setRoles(['content_editor', 'video_editor']);
        $this->assertTrue($user->hasRole('content_editor'));
        $this->assertTrue($user->hasRole('video_editor'));
        $this->assertTrue($user->hasCapability('edit_posts'));
        $this->assertTrue($user->hasCapability('edit_videos'));
    }

    /**
     * @test
     */
    public function invalid_roles_cannot_be_added()
    {
        $this->setupDatabase();
        $user = $this->createUser();
        $this->assertFalse($user->addRole('not a role'));
        $this->assertEmpty($user->getRoles());
    }

    /**
     * @test
     */
    public function roles_can_be_removed()
    {
        $this->setupDatabase();
        $user = $this->createUser('content_editor');
        $this->assertTrue($user->can('edit_posts'));

        $user->removeRole('content_editor');
        $this->assertFalse($user->can('edit_posts'));
    }
}
