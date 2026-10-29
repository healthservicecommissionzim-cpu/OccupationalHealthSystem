<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'name' => '',
    'placeholder' => '',
    'value' => null,
    'error' => null,
    'icon' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'id' => null,
    'rows' => 5,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'label' => null,
    'name' => '',
    'placeholder' => '',
    'value' => null,
    'error' => null,
    'icon' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'id' => null,
    'rows' => 5,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = $id ?? $name;
    $hasError = $error || $errors->has($name);
?>

<div class="form-group">
    <?php if($label): ?>
        <label for="<?php echo e($id); ?>" class="<?php if($hasError): ?> error <?php endif; ?>">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="required" aria-label="required">*</span>
            <?php endif; ?>
            <?php if($helper): ?>
                <span class="helper"><?php echo e($helper); ?></span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

    <div class="input-wrapper <?php if($hasError): ?> error <?php endif; ?>" id="<?php echo e($id); ?>-wrapper">
        <textarea
            id="<?php echo e($id); ?>"
            name="<?php echo e($name); ?>"
            placeholder="<?php echo e($placeholder); ?>"
            rows="<?php echo e($rows); ?>"
            <?php if($required): ?> required <?php endif; ?>
            <?php if($disabled): ?> disabled <?php endif; ?>
            <?php if($hasError): ?> aria-invalid="true" aria-describedby="<?php echo e($id); ?>-error" <?php endif; ?>
            class="<?php if($hasError): ?> has-error <?php endif; ?>"
            <?php echo e($attributes->merge(['class' => ''])); ?>

        ><?php echo e(old($name, $value)); ?></textarea>
        
        <?php if($icon): ?>
            <span class="input-icon">
                <i class="fa-solid fa-<?php echo e($icon); ?>"></i>
            </span>
        <?php endif; ?>
    </div>

    <?php if($hasError): ?>
        <div class="error-text" id="<?php echo e($id); ?>-error" role="alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <?php
                $errorMessage = $error ?? $errors->first($name);
            ?>
            <?php echo e($errorMessage); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\OccupationalHealth System\resources\views\components\form-textarea.blade.php ENDPATH**/ ?>