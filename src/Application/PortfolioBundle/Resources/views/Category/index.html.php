<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Categories list</h4>

<?php if ($categories): ?>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="<?php echo $view['router']->generate('portfolioCategoryView', array('id' => $category['id'])) ?>">
                    <?php echo $category['name']; ?>
                </a>
                <?php if ($view['security']->vote('ROLE_ADMIN')): ?>
                    (<a href="<?php echo $view['router']->generate('portfolioCategoryEdit', array('id' => $category['id'])) ?>">Edit Category</a>
                    / <a href="<?php echo $view['router']->generate('portfolioCategoryDelete', array('id' => $category['id'])) ?>">Delete Category</a>)
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="info">
        List of categories is empty
    </div>
<?php endif; ?>
