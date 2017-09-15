<?php

namespace app\doctrineModels;

/**
 * @Entity()
 * @Table(name="todo_share")
 */
class TodoShare extends BaseDoctrineModel
{
    /**
     * @Id()
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer", name="id")
     */
    protected $todo_share_id;

    /**
     * @ManyToMany(targetEntity="User", mappedBy="users")
     */
    protected $usersShare;

    /**
     * @ManyToOne(targetEntity="Todo")
     * @JoinColumn(name="todo_id", referencedColumnName="todo_id", nullable=false)
     */
    protected $todo;

    /**
     * @Column(type="smallint", nullable=false, options={"default": 0})
     */
    protected $is_editable = 0;
}