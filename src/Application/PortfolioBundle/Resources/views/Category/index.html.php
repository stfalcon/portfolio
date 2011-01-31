<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Categories list</h4>

<?php if ($categories): ?>
    <table>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td>
                        <a href="<?php echo $view['router']->generate('portfolioCategoryView', array('id' => $category['id'])) ?>">
                            <?php echo $category['name']; ?>
                        </a>
                    </td>
                    <td class="currency">
                        <a href="<?php echo $view['router']->generate('portfolioCategoryEdit', array('id' => $category['id'])) ?>">Edit Category</a>
                        / <a href="<?php echo $view['router']->generate('portfolioCategoryDelete', array('id' => $category['id'])) ?>">Delete Category</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="info">
        List of categories is empty
    </div>
<?php endif; ?>
