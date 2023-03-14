<template>
  <CHeader fixed with-subheader>
    <router-link :to="`/games`" style="width: calc(100% - 60px)">
      <img
        src="img/brand/logo.png"
        alt="logo"
        style="width: 150px; margin-top: 5px"
      /></router-link>
    <CHeaderNav>
      <TheHeaderDropdownAccnt />
    </CHeaderNav>
  </CHeader>
</template>

<script>
import TheHeaderDropdownAccnt from "./TheHeaderDropdownAccnt";
import u from "../utilities/utility";
export default {
  name: "TheHeader",
  components: {
    TheHeaderDropdownAccnt,
  },
  data: () => {
    return {
      user_id: u.session().user_info.user_id,
    };
  },
  sockets: {
    connect: function () {
      console.log("socket to notification channel connected");
    },
  },
  created() {
    this.$socket.emit("userConnected", {
      user_id: this.user_id,
    });
  },
  methods: {},
};
</script>