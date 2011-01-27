<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Projects list</h4>

<?php if ($projects): ?>
    <table>
        <tbody>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td>
                        <?php echo $project['name']; ?>
                    </td>
                    <td>
                        <?php echo $project['description']; ?>
                    </td>
                    <td class="currency">
                        <a href="<?php echo $view['router']->generate('portfolioProjectEdit', array('id' => $project['id'])) ?>">Edit project</a>
                        / <a href="<?php echo $view['router']->generate('portfolioProjectDelete', array('id' => $project['id'])) ?>">Delete project</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="info">
        List of projects is empty
    </div>
<?php endif; ?>
