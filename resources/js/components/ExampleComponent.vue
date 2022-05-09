<template>
  <div class="container">
    <div class="row">
      <div class="col" v-if="showSaved">
        <ul>
          <li v-for="(address, index) in addresses" :key="index">
            {{ address }}
          </li>
        </ul>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <strong>Please enter in an address hi</strong>
          </div>

          <b-form-group>
            <b-input-group>
              <b-form-input placeholder="Address" v-model="address">
              </b-form-input>
              <b-form-input
                placeholder="Apartment # (optional)"
                v-model="apartment"
              >
              </b-form-input>
              <b-form-input placeholder="City" v-model="city"> </b-form-input>
              <b-form-input placeholder="State" v-model="state"> </b-form-input>
              <b-form-input placeholder="Zip" v-model="zip"> </b-form-input>
            </b-input-group>
            <b-button varient="success" @click="submitAddress">Submit</b-button>
          </b-form-group>
        </div>
      </div>
      <div class="row">
        <b-button @click="toggleSaved">Show Saved Addresses</b-button>
      </div>
      <div class="col-md-8">
        <div class="card" v-if="error" style="color: red">
          {{ errorMessage }}
        </div>
        <div class="card" v-if="submitted">
          <div class="container">
            <div class="row">
              <p>You submitted the address</p>
              <p>
                {{ address }} {{ apartment }} {{ city }} {{ state }} {{ zip }}
              </p>
              <p>The correct address is</p>
              <p style="color: green">{{ printCorrectedAddress }}</p>
            </div>
            <div class="col">
              Which address would you like to save?
              <b-button varient="success" @click="onSubmittedClick"
                >Submitted</b-button
              >
              <b-button varient="success" @click="onCorrectedClick"
                >Correct</b-button
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  props: ["addresses"],
  data() {
    return {
      address: "",
      showSaved: false,
      apartment: "",
      city: "",
      state: "",
      zip: "",
      error: false,
      submitted: false,
      errorMessage: "",
      successMessage: "",
      correctAddress: "",
    };
  },
  computed: {
    printCorrectedAddress() {
      return (
        this.correctAddress.address +
        " " +
        this.correctAddress.apartment +
        " " +
        this.correctAddress.city +
        " " +
        this.correctAddress.state +
        " " +
        this.correctAddress.zip
      );
    },
  },
  methods: {
    submitAddress() {
      axios
        .post("/api/address", {
          address: this.address,
          apartment: this.apartment,
          city: this.city,
          state: this.state,
          zip: this.zip,
        })
        .then((response) => {
          console.log(response);
          this.submitted = true;
          this.correctAddress = {
            address: response.data.address["Address2"],
            apartment: response.data.address["Address1"] ? undefined : "",
            city: response.data.address["City"],
            state: response.data.address["State"],
            zip:
              response.data.address["Zip5"] +
              "-" +
              response.data.address["Zip4"],
          };
          if (response.data.error) {
            this.error = true;
            this.errorMessage = response.data.error;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    saveAddress(address) {
      axios.post("/api/save", address).then((response) => {
        console.log(response);
      });
    },
    onCorrectedClick() {
      this.saveAddress(this.correctAddress);
    },
    onSubmittedClick() {
      this.saveAddress({
        address: this.address,
        apartment: this.apartment,
        city: this.city,
        state: this.state,
        zip: this.zip,
      });
    },
    toggleSaved() {
      this.showSaved = !this.showSaved;
    },
  },
};
</script>
