<?php

namespace Blog\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;

class ZendDbSqlRepository implements PostRepositoryInterface
{
    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var Post
     */
    private $postPrototype;

    /**
     * ZendDbSqlRepository constructor.
     * @param AdapterInterface $dbRead
     * @param AdapterInterface $dbWrite
     * @param HydratorInterface $hydrator
     * @param Post $postPrototype
     */
    public function __construct(
        AdapterInterface $dbRead,
        AdapterInterface $dbWrite,
        HydratorInterface $hydrator,
        Post $postPrototype
    )
    {
        $this->dbRead = $dbRead;
        $this->dbWrite = $dbWrite;

        $this->hydrator = $hydrator;
        $this->postPrototype = $postPrototype;
    }

    /**
     * Return a set of all blog posts that we can iterate over.
     *
     * Each entry should be a Post instance.
     *
     * @param bool $paginated
     * @param int $page
     * @return Post[]
     */
    public function findAllPosts($paginated = false, $page = 1)
    {
        $sql = new Sql($this->dbRead);
        $select = $sql->select('posts');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            return [];
        }

        if ($paginated && $page) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\DbSelect($select, $this->dbRead, $resultSet));
            $paginator->setItemCountPerPage(3);
            $paginator->setCurrentPageNumber($page);
        } else {
            $paginator = null;
            $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);
            $resultSet->initialize($result);
        }

        return ['posts' => $resultSet, 'paginator' => $paginator];
    }

    /**
     * Return a single blog post.
     *
     * @param  int $id Identifier of the post to return.
     * @return Post
     */
    public function findPost($id)
    {
        $sql = new Sql($this->dbRead);
        $select = $sql->select('posts');
        $select->where(['id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving blog post with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);
        $resultSet->initialize($result);
        $post = $resultSet->current();

        if (!$post) {
            throw new InvalidArgumentException(sprintf(
                'Blog post with identifier "%s" not found.',
                $id
            ));
        }

        return $post;
    }
}