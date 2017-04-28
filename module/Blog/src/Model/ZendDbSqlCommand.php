<?php

namespace Blog\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class ZendDbSqlCommand implements PostCommandInterface
{
    /**
     * @var AdapterInterface
     */
    private $dbRead;

    /**
     * @var AdapterInterface
     */
    private $dbWrite;

    /**
     * @param AdapterInterface $dbRead
     * @param AdapterInterface $dbWrite
     */
    public function __construct(AdapterInterface $dbRead, AdapterInterface $dbWrite)
    {
        $this->dbRead = $dbRead;
        $this->dbWrite = $dbWrite;
    }

    /**
     * {@inheritDoc}
     */
    public function insertPost(Post $post)
    {
        $insert = new Insert('posts');
        $insert->values([
            'title' => $post->getTitle(),
            'text' => $post->getText(),
        ]);

        $sql = new Sql($this->dbWrite);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during blog post insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Post(
            $post->getTitle(),
            $post->getText(),
            $result->getGeneratedValue()
        );
    }

    /**
     * @param Post $post
     * @return Post
     */
    public function updatePost(Post $post)
    {
        if (!$post->getId()) {
            throw new RuntimeException('Cannot update post. Missing ID');
        }

        $update = new Update('posts');
        $update->set([
            'title' => $post->getTitle(),
            'text' => $post->getText(),
        ]);
        $update->where(['id = ?' => $post->getId()]);

        $sql = new Sql($this->dbWrite);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during blog post update operation'
            );
        }

        return $post;
    }

    /**
     * @param Post $post
     * @return bool
     */
    public function deletePost(Post $post)
    {
        if (!$post->getId()) {
            throw new RuntimeException('Cannot update post. Missing ID');
        }

        $delete = new Delete('posts');
        $delete->where(['id = ?' => $post->getId()]);

        $sql = new Sql($this->dbWrite);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}