<?php
/**
 * Abstract Model Class
 * 
 * Base model class providing common database operations using Active Record pattern
 * Implements CRUD operations and query building with PDO prepared statements
 * Supports flexible query conditions, parameter binding, and custom SQL execution
 * 
 * @package Models
 * @abstract
 * @author Akshay
 * @version 1.0
 */
abstract class Model {
    
    /**
     * @var PDO $db PDO database connection instance
     */
    protected $db;
    
    /**
     * @var string $table Database table name associated with the model
     */
    protected $table;
    
    /**
     * @var string $primaryKey Primary key column name (default: 'id')
     */
    protected $primaryKey = 'id';

    /**
     * Constructor
     * 
     * Initializes database connection using Singleton pattern
     * Must be extended by concrete model classes that define $table property
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve all records from the table
     * 
     * Fetches complete dataset with no filtering or pagination
     * Suitable for small datasets or administrative interfaces
     * 
     * @return array Array of all records as associative arrays
     */
    public function all() {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    /**
     * Find record by primary key
     * 
     * Retrieves single record using primary key lookup
     * Uses prepared statements to prevent SQL injection
     * 
     * @param mixed $id Primary key value to search for
     * @return array|null Associative array of record data or null if not found
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new record
     * 
     * Inserts new record into database with provided data
     * Automatically builds INSERT query with parameter binding
     * 
     * @param array $data Associative array of column => value pairs
     * @return bool True on success, false on failure
     */
    public function create($data) {
        // Build column list and named placeholders for prepared statement
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        return $stmt->execute($data);
    }

    /**
     * Find records with conditions
     * 
     * Builds WHERE clause dynamically from conditions array
     * Supports multiple conditions with AND conjunction
     * Uses named parameters for safe value binding
     * 
     * @param array $conditions Associative array of column => value conditions
     * @return array Array of matching records as associative arrays
     * 
     * @example
     * $userModel->where(['status' => 'active', 'role' => 'admin'])
     * // Generates: SELECT * FROM users WHERE status = :status AND role = :role
     */
    public function where($conditions) {
        // Build WHERE clause with named parameters
        $whereClause = implode(' AND ', array_map(function($col) {
            return "$col = :$col";
        }, array_keys($conditions)));
        
        // Construct SQL query
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($whereClause)) {
            $sql .= " WHERE {$whereClause}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll();
    }

    /**
     * Execute custom SQL query
     * 
     * Provides flexibility for complex queries not covered by standard methods
     * Maintains security with parameter binding for user input
     * 
     * @param string $sql SQL query string with placeholders
     * @param array $params Associative array of parameters for prepared statement
     * @return array Query results as associative arrays
     * 
     * @example
     * $this->query(
     *     "SELECT * FROM users WHERE created_at > :date AND status = :status",
     *     ['date' => '2023-01-01', 'status' => 'active']
     * )
     */
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Update existing record
     * 
     * Updates record by primary key with provided data
     * Builds dynamic SET clause with parameter binding
     * 
     * @param mixed $id Primary key value of record to update
     * @param array $data Associative array of column => value pairs to update
     * @return bool True on success, false on failure
     */
    public function update($id, $data) {
        // Build SET clause with named parameters
        $setClause = implode(', ', array_map(function($col) {
            return "$col = :$col";
        }, array_keys($data)));
        
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id"
        );
        
        // Combine data with ID for parameter binding
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Delete record by primary key
     * 
     * Removes single record from database using primary key
     * 
     * @param mixed $id Primary key value of record to delete
     * @return bool True on success, false on failure
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
}