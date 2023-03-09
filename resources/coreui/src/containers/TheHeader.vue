<template>
  <CHeader fixed with-subheader>
    <a class="header-brand" href="#" style="width: calc(100% - 60px)">
      <img
        src="img/brand/coreui-vue-logo.svg"
        alt="logo"
        style="width: 150px; margin-top: 5px"
      />
    </a>
    <CHeaderNav>
      <TheHeaderDropdownAccnt />
    </CHeaderNav>
  </CHeader>
</template>

<script>
import TheHeaderDropdownAccnt from "./TheHeaderDropdownAccnt";

export default {
  name: "TheHeader",
  components: {
    TheHeaderDropdownAccnt,
  },
  data() {
    return {};
  },
  sockets: {
    connect: function () {
      console.log("socket to notification channel connected");
    },
  },
  created() {
    this.$socket.emit("userConnected", {
      user_id: "123",
    });
  },
  methods: {},
};
</script>