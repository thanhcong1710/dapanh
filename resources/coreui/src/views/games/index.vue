<template>
  <div>
    <loader :active="loading.processing" :text="loading.text" />
  </div>
</template>

<script>
import u from "../../utilities/utility";
import loader from "../../components/Loading";

export default {
  name: "Games",
  data: () => {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
    };
  },
  methods: {
    createRoom() {
      const data = {
        game_id: "1",
      };
      u.p(`/api/rooms/create`, data)
        .then((response) => {
          this.loading.processing = false;
          if (response.status == 1) {
            this.$router.push({ path: `/rooms/${response.room_code}/detail` });
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
  },
};
</script>

