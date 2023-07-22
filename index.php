<?php 
include 'dbcreate.php';


$table = new DBCreate();
function table(DBCreate $table) {

    $sql = $table->string('name')->nullable()
                ->integer('age')->nullable()
                ->decimal('price', 10, 2)
                ->boolean('status')
                ->timestamp('created_at')
                ->build('my_table');
                echo $sql;
}
// $result = $table->sql($sql);
// $result = $table->delete_table('my_table');
table($table);
?>