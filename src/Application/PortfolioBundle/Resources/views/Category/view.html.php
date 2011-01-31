<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Category "<?php echo $category->getName(); ?>"</h4>

<?php if (count($category->getProjects())): ?>
    <table>
        <tbody>
            <?php foreach ($category->getProjects() as $project): ?>
                <tr>
                    <td>
                        <?php echo $project->getName(); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="info">
        This category is empty
    </div>
<?php endif; ?>
