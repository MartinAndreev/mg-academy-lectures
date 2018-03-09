<?php load_header($data); ?>

<div class="container">

    <div class="text-right" style="margin-bottom: 30px;">
        <a href="index.php?action=add_book" class="btn btn-primary">
            Add book
        </a>
        
        <hr />
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    ISBN
                </th>
                <th>
                    Name
                </th>
                <th>
                    Author
                </th>
                <th>
                    Last update
                </th>
                <th>
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($data['books']) && count($data['books']) > 0): ?>
                <?php foreach ($data['books'] as $book): ?>
                    <tr>
                        <td>
                            <?php echo $book['isbn']; ?>
                        </td>
                        <td>
                            <?php echo $book['book']; ?>
                        </td>
                        <td>
                            <?php echo $book['author']; ?>
                        </td>
                        <td>
                            <?php echo date('d.m.Y H:i:s', $book['updated_on']); ?>
                        </td>
                        <td>
                            <a class="btn btn-xs btn-success" href="index.php?action=edit_book&id=<?php echo $book['id']; ?>">
                                Edit
                            </a> 
                            <a class="btn btn-xs btn-danger" href="index.php?action=delete_book&id=<?php echo $book['id']; ?>" onclick="return confirm('Are you sure?')">
                                Delete
                            </a> 
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">
                        No books found
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php load_footer($data); ?>