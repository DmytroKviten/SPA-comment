import './bootstrap';

import { createApp } from 'vue';
import AvatarGenerator from './components/AvatarGenerator.vue';

const app = createApp({});

app.component('avatar-generator', AvatarGenerator);

app.mount('#app');