<?php
$this->title = 'Переводчики';
?>

<div class="site-translators">
    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>

    <div id="app">
        <div class="mb-3">
            <label class="form-label">Дата</label>
            <input type="date" class="form-control" v-model="date" @change="load()">
        </div>

        <div class="alert alert-info" role="alert">
            {{ statusMessage }}
        </div>

        <table class="table table-striped" v-if="items.length > 0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="t in items" :key="t.id">
                    <td>{{ t.id }}</td>
                    <td>{{ t.name }}</td>
                    <td>{{ t.email }}</td>
                </tr>
            </tbody>
        </table>

        <div v-else class="text-muted">
            Нет данных
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
    const {
        createApp,
        ref,
        onMounted
    } = Vue;

    createApp({
        setup() {
            const today = new Date().toISOString().slice(0, 10);
            const date = ref(today);
            const items = ref([]);
            const statusMessage = ref('Загрузка...');

            const load = async () => {
                const [listResp, statusResp] = await Promise.all([
                    fetch(`/api/translators?date=${encodeURIComponent(date.value)}`),
                    fetch(`/api/translators/status?date=${encodeURIComponent(date.value)}`)
                ]);

                const {
                    items: listItems
                } = await listResp.json();
                const {
                    message
                } = await statusResp.json();

                items.value = listItems || [];
                statusMessage.value = message || '—';
            };

            onMounted(load);

            return {
                date,
                items,
                statusMessage,
                load
            };
        }
    }).mount('#app');
</script>