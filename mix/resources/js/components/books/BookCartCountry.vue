<template>
    <div class="book-cart-country">
        <slot></slot>
        <div class="invalid-feedback" style="display: block" v-if="showMessage">
            Please read the important information above regarding international shipments. <!-- TODO Localize -->
        </div>
    </div>
</template>

<script>

// See https://github.com/abhayagiri/abhayagiri-website/issues/120

export default {

    data() {
        const data = {
            handleChange(el) {
                console.log('handleChange');
                data.showMessage = data.isOutsideUs(el.target.value);
                console.log(data.showMessage);
            },
            isOutsideUs(value) {
                if (typeof value !== 'string') {
                    return false;
                }
                value = value.trim().toLowerCase();
                return (value !== '' && value !== 'us' && value !== 'usa' &&
                        value !== 'united states');
            },
            showMessage: false,
        };
        return data;
    },

    mounted () {
        this.$slots.default.forEach(vnode => {
            if (vnode.tag === 'input') {
                vnode.elm.addEventListener('change', this.handleChange, true);
            }
        });
    },
}

</script>
