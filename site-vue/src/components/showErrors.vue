<template>
    <div class="showErrors">
        <table class="table">
            <thead>
            <tr>
                <th>Code</th>
                <th>Message</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="error in errors">
                    <td>{{ error.code }}</td>
                    <td>{{ error.message }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'showErrors',
        props: {
            errors: Array
        },
    };
</script>
