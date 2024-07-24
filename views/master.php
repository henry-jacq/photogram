<?php
use App\Enum\PreferredTheme;

if (isset($params['user'])) {
    $theme = $params['user']->getPreferences()->getTheme();
} else {
    $theme = PreferredTheme::Dark->value;
} ?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo($theme); ?>">

<?php echo ($this->renderLayout('head', $params)); ?>

<body>

    {{header}}

    <section class="container">
        {{contents}}
    </section>

    {{footer}}

    <?php echo ($this->renderComponent('scroll', $params)); ?>
    <?php echo ($this->renderComponent('modal', $params)); ?>
    <?php echo ($this->renderLayout('script', $params)); ?>
</body>

</html>