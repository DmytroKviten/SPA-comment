<template>
  <div class="avatar-generator">
    <h2>Генератор персонажу</h2>
    <div class="controls">
      <label>
        Стать:
        <select v-model="gender">
          <option value="male">Чоловік</option>
          <option value="female">Жінка</option>
        </select>
      </label>
      <label>
        Колір шкіри:
        <input type="color" v-model="skinColor" />
      </label>
      <label>
        Колір волосся:
        <input type="color" v-model="hairColor" />
      </label>
      <label>
        Колір очей:
        <input type="color" v-model="eyeColor" />
      </label>
    </div>
    <div class="avatar-display">
      <img :src="avatarUrl" alt="Avatar" class="avatar-image" />
    </div>
  </div>
</template>

<script>
export default {
  name: "AvatarGenerator",
  data() {
    return {
      gender: "male",
      skinColor: "#f1c27d",
      hairColor: "#4b3621",
      eyeColor: "#0000ff"
    }
  },
  computed: {
    avatarUrl() {
      // Видаляємо '#' із кольорів
      const skin = this.skinColor.replace('#', '');
      const hair = this.hairColor.replace('#', '');
      const eyes = this.eyeColor.replace('#', '');
      // Вибір зачіски залежно від статі
      const top = this.gender === 'male'
        ? 'shortHairShortFlat'
        : 'longHairStraight2';
      // Можна передавати будь-який seed
      const seed = 'custom-avatar';
      // Формуємо URL із параметрами DiceBear (стиль avataaars)
      return `https://avatars.dicebear.com/api/avataaars/${seed}.svg?top[]=${top}&skinColor[]=${skin}&hairColor[]=${hair}&eyeColor[]=${eyes}`;
    }
  }
}
</script>

<style scoped>
.avatar-generator {
  text-align: center;
  margin: 20px;
}
.controls {
  margin-bottom: 20px;
}
.controls label {
  display: block;
  margin: 10px 0;
  font-size: 1em;
}
.avatar-display {
  border: 1px solid #ccc;
  display: inline-block;
  padding: 10px;
  border-radius: 8px;
}
.avatar-image {
  width: 200px;
  height: 200px;
}
</style>
