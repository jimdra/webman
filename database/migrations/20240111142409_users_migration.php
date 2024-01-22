<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UsersMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('users', ['comment' => '后台用户表']);
        $table->addColumn('username', 'string', ['comment' => '用户名'])
            ->addColumn('nickname', 'string', ['comment' => '昵称', 'null' => true])
            ->addColumn('email', 'string', ['comment' => '邮箱', 'null' => true])
            ->addColumn('password', 'string', ['comment' => '密码'])
            ->addColumn('created_at', 'datetime', ['comment' => '创建时间', 'null' => true])
            ->addColumn('updated_at', 'datetime', ['comment' => '更新时间', 'null' => true])
            ->addColumn('login_at', 'datetime', ['comment' => '登录时间', 'null' => true])
            ->addIndex(['username'], ['unique' => true])
            ->create();
    }
}
