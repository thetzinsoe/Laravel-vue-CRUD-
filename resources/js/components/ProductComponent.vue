<template>
    <div class="container">
        <vue-progress-bar></vue-progress-bar>
        <loading :active="isLoading" :is-full-page="fullPage" :color="green" />
        <div class="row mt-5 mb-4">
            <div class="col-md-8 offset-md-4 d-flex justify-content-between">
                <div>
                    <button class="btn btn-primary" @click="create">
                        <i class="fas fa-circle-plus"></i>
                        Create
                    </button>
                </div>
                <form
                    class="form d-flex justify-content-end"
                    @submit.prevent="view"
                >
                    <div class="input-group">
                        <input
                            v-model="searchData"
                            type="text"
                            class="form-control"
                            placeholder="Search with name"
                        />
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isEditMode ? "Edit" : "Create" }}</h4>
                    </div>
                    <div class="card-body">
                        <AlertError
                            :form="product"
                            message="Invalid Input. Try Again!"
                        />
                        <form
                            @submit.prevent="isEditMode ? update() : store()"
                            @keydown="product.onKeydown($event)"
                        >
                            Name:<input
                                v-model="product.name"
                                type="text"
                                class="form-control"
                            />
                            <div
                                class="text-danger"
                                v-if="product.errors.has('name')"
                                v-html="product.errors.get('name')"
                            />

                            Price:
                            <input
                                v-model="product.price"
                                type="text"
                                class="form-control"
                            />
                            <div
                                class="text-danger"
                                v-if="product.errors.has('price')"
                                v-html="product.errors.get('price')"
                            />
                            <button
                                type="submit"
                                class="bg-primary btn text-white mt-3"
                                :disabled="product.busy"
                            >
                                <i class="fas fa-save mr-1"></i> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table">
                    <thead>
                        <tr class="table-secondary">
                            <th>ID</th>
                            <th>NAME</th>
                            <th>PRICE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products.data" :key="product.id">
                            <td>{{ product.id }}</td>
                            <td>{{ product.name }}</td>
                            <td>${{ product.price }}</td>
                            <td>
                                <button
                                    class="btn btn-success btn-sm mx-2"
                                    @click="edit(product)"
                                >
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="destory(product.id)"
                                    :disabled="product.busy"
                                >
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Bootstrap5Pagination
                    :data="products"
                    @pagination-change-page="view"
                />
                <vue-progress-bar></vue-progress-bar>
            </div>
        </div>
    </div>
</template>

<script>
import Form from "vform";
import Swal from "sweetalert2";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import { AlertError } from "vform/src/components/bootstrap5";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import axios from "axios";
export default {
    name: "ProductComponent",
    components: { Bootstrap5Pagination, AlertError, Loading },
    data() {
        return {
            isLoading: false,
            fullPage: true,
            isEditMode: false,
            products: {},
            product: new Form({
                id: "",
                name: "",
                price: "",
            }),
            searchData: "",
        };
    },
    methods: {
        view(page = 1) {
            this.$Progress.start();
            this.isLoading = true;
            axios
                .get(`/api/product?page=${page}&search=${this.searchData}`)
                .then((res) => {
                    this.isLoading = false;
                    this.products = res.data;
                    this.$Progress.finish();
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.log(err);
                    this.$Progress.finish();
                });
        },
        store() {
            this.isLoading = true;
            this.product
                .post("/api/product")
                .then((res) => {
                    this.isLoading = false;
                    Toast.fire({
                        icon: "success",
                        title: "Create successfully",
                    });
                    this.product.reset();
                    this.view();
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.log(err.response.data.errors);
                });
        },
        edit(product) {
            (this.isEditMode = true), this.product.fill(product);
        },
        update() {
            this.$Progress.start();
            this.isLoading = true;
            this.product
                .put(`/api/product/${this.product.id}`)
                .then((res) => {
                    this.isLoading = false;
                    this.isEditMode = false;
                    Toast.fire({
                        icon: "success",
                        title: "Update successfully",
                    });
                    this.product.reset();
                    this.view();
                    this.$Progress.finish();
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.log(err);
                    this.$Progress.finish();
                });
        },
        destory(id) {
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.product
                        .delete(`/api/product/${id}`)
                        .then((res) => {
                            Toast.fire({
                                icon: "success",
                                title: "Delete successfully",
                            });
                            this.view();
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            });
        },
        create() {
            (this.isEditMode = false), this.product.reset();
        },
        search() {
            this.product
                .get("/api/product")
                .then((res) => {
                    this.products = res.data.filter((r) => {
                        return r.name === this.searchData;
                    });
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
    created() {
        this.view();
    },
};
</script>

<style></style>
