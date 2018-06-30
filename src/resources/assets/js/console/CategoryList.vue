<style scoped>
</style>

<template>

    <div ui-jq="nestable" class="dd">
        <ol class="dd-list">
            <li class="dd-item" data-id="1">
                <div class="dd-handle">Item 1</div>
            </li>
            <li class="dd-item" data-id="2">
                <div class="dd-handle">Item 2</div>
                <ol class="dd-list">
                    <li class="dd-item" data-id="3"><div class="dd-handle">Item 3</div></li>
                    <li class="dd-item" data-id="4"><div class="dd-handle">Item 4</div></li>
                    <li class="dd-item" data-id="5">
                        <div class="dd-handle">Item 5</div>
                        <ol class="dd-list">
                            <li class="dd-item" data-id="6"><div class="dd-handle">Item 6</div></li>
                            <li class="dd-item" data-id="7"><div class="dd-handle">Item 7</div></li>
                            <li class="dd-item" data-id="8"><div class="dd-handle">Item 8</div></li>
                        </ol>
                    </li>
                    <li class="dd-item" data-id="9"><div class="dd-handle">Item 9</div></li>
                </ol>
            </li>
        </ol>
    </div>

</template>

<script>
    export default{
        props: [],
        data(){
            return {
                resourcePath: 'categories',

                orderBy: 'someline_category_id',
                sortedBy: 'asc',

                items: [
                    {
                        id: 1,
                        name: 'A',
                    },
                    {
                        id: 2,
                        name: 'B',
                    },
                    {
                        id: 3,
                        name: 'C',
                    },
                    {
                        id: 4,
                        name: 'C1',
                        parent_id: 3,
                    },
                    {
                        id: 5,
                        name: 'C12',
                        parent_id: 4,
                    },
                    {
                        id: 6,
                        name: 'C2',
                        parent_id: 3,
                    },
                ],

                orderableFields: [
                    {
                        name: 'someline_category_id',
                        display: '序号',
                    },
                    {
                        name: 'created_at',
                        display: '创建时间',
                    },
                    {
                        name: 'updated_at',
                        display: '更新时间',
                    },
                ],
            }
        },
        computed: {},
        components: {
//            'sl-user-list-item': require('./UserListGroupItem.vue'),
        },
        http: {
            root: '/api',
            headers: {
                Accept: 'application/x.someline.v1+json'
            }
        },
        mounted(){
            console.log('Component Ready.');

//            this.eventEmit('SomelineTable.doFetchData');
        },
        watch: {},
        events: {},
        methods: {
            handleEdit(index, row) {
                console.log(index, row);
            },
            handleDelete(index, row) {
                console.log(index, row);
            },
            getSearchValue(val){
                console.log('getSearchValue: ', val);
                if (val && val.length > 0) {
                    return 'a:' + val + '';
                } else {
                    return '';
                }
            },
            onResourceResponse(response){
                console.log('response: ', response);
                console.log('response url: ', response.url);
            },
            handleSelectionChange(val){
                console.log('handleSelectionChange', val);
            },
        },
    }
</script>