<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */
namespace TestObjects;

class Exporter
{
    /**
     *
     * @var PDO
     */
    private $db1 = null;

    /**
     *
     * @var PDO
     */
    private $db2 = null;

    public function __construct()
    {
        $this->db1 = new DbAdapter('mysql:dbname=db1;host=127.0.0.1', 'user', 'pass');
        $this->db2 = new DbAdapter('mysql:dbname=db2;host=127.0.0.1', 'user', 'pass');
        $this->logger = new Logger();
    }

    public function process()
    {
        $this->exportCategories();
        $this->exportFilters();
        $this->exportImages();
        $this->exportProducts();
    }

    private function execDb1($query)
    {
        $this->log("export query: ", $query);
        $this->db1->exec($query);
    }

    private function execDb2($query)
    {
        $this->log("export query: ", $query);
        $this->db2->exec($query);
    }

    private function exportProducts()
    {
        $products = $this->db1->exec('SELECT * FROM products');
    }

    private function exportFilters()
    {
        $filters = $this->db1->exec('SELECT * FROM filters');
    }

    private function exportImages()
    {
        $filters = $this->db1->exec('SELECT * FROM images');
    }
    private function exportNews()
    {
        echo "muhu";
        $test = "neu";
        if (!$test) {
            $lulu = "muhu";
        }
        # haloo
    }

    private function exportCategories()
    {
        $filters = $this->db1->exec('SELECT * FROM categories');
    }

    private function log($msg, $content)
    {
        # logging
    }
}
