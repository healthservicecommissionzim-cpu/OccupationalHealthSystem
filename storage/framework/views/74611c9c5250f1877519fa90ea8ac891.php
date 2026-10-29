<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'name' => '',
    'value' => '1',
    'checked' => false,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'id' => null,
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
    'value' => '1',
    'checked' => false,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'id' => null,
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
    $isChecked = $checked || old($name) == $value;
?>

<div class="checkbox-group <?php if($hasError): ?> error <?php endif; ?>" id="<?php echo e($id); ?>-wrapper">
    <input
        type="checkbox"
        id="<?php echo e($id); ?>"
        name="<?php echo e($name); ?>"
        value="<?php echo e($value); ?>"
        <?php if($isChecked): ?> checked <?php endif; ?>
        <?php if($required): ?> required <?php endif; ?>
        <?php if($disabled): ?> disabled <?php endif; ?>
        <?php if($hasError): ?> aria-invalid="true" aria-describedby="<?php echo e($id); ?>-error" <?php endif; ?>
        <?php echo e($attributes->merge(['class' => ''])); ?>

    />
    
    <?php if($label): ?>
        <label for="<?php echo e($id); ?>">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="required" aria-label="required">*</span>
            <?php endif; ?>
            <?php if($helper): ?>
                <span class="helper"><?php echo e($helper); ?></span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

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
<?php /**PATH D:\OccupationalHealth System\resources\views\components\form-checkbox.blade.php ENDPATH**/ ?>