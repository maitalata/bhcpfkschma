<script setup>

import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';

const selectedLGA = ref(false);
const hasSelectedWard = ref(false);
const hasSelectedCommunity = ref(false);
const selectedWard = ref('');
const selectedCommunity = ref('');
const step = ref(1);
const selectedLocalGovernment = ref('');
const householdNum = ref(1)

const nextStep = () => {
    step.value += 1;
}

const previousStep = () => {
    step.value -= 1;
}

const LGASelected = () => {
    selectedLGA.value = true;
}

const props = defineProps({
    LocalGovernments: {
        type: Object,
        required: true,
    },
});


onMounted(() => {
    console.log(props.LocalGovernments);
});

const secondOptions = ref([]);
const thirdOptions = ref([]);
const beneficiaries = ref([]);

const fetchWards = () => {
    if (selectedLocalGovernment.value) {
        axios.get(`/fetchWards/${selectedLocalGovernment.value}`)
            .then((response) => {
                console.log('Options fetched:', response.data);
                secondOptions.value = response.data; // Assigning the fetched wards
                selectedLGA.value = true;
            })
            .catch((error) => {
                console.error('Error fetching options:', error);
            });
    } else {
        console.error('Local government is not selected');
    }
    // router.get(route('fetchWards', { local_government: selectedLocalGovernment.value }), {
    //     onSuccess: (page) => {
    //         console.log('Options fetched:', page.props.wardOptions);
    //         secondOptions = page.props.wardOptions;
    //     },
    //     onError: (error) => {
    //         console.error('Error fetching options:', error);
    //     },


    // });
}

const fetchCommunities = () => {
    if (selectedWard.value) {
        axios.get(`/fetchCommunites/${selectedWard.value}`)
            .then((response) => {
                console.log('Options fetched:', response.data);
                thirdOptions.value = response.data; // Assigning the fetched communities
                hasSelectedWard.value = true;
                //selectedWard.value = '';
            })
            .catch((error) => {
                console.error('Error fetching options:', error);
            });
    } else {
        console.error('Ward is not selected');
    }
}

const fetchBeneficiaries = () => {
    if (selectedWard.value) {
        axios.get(`/fetchBeneficiaries/${selectedWard.value}`)
            .then((response) => {
                console.log('Options fetched:', response.data);
                beneficiaries.value = response.data; // Assigning the fetched communities
                hasSelectedCommunity.value = true;
                //selectedWard.value = '';
            })
            .catch((error) => {
                console.error('Error fetching options:', error);
            });
    } else {
        console.error('Community is not selected');
    }
}

</script>
<template>
    <div class="">
        <div class="mb-10">
            <h1 class="p-8 text-xl text-center">KANO STATE BASIC HEALTHCARE PROVISION FUND ENROLLEE VERIFICATION
                EXERCISE FORM</h1>
        </div>
        <br>

        <!-- Select Box for Local Government -->
        <div v-if="step === 1">
            <div class="flex justify-around text-center">
                <div class="">
                    <label for="lga" class="block text-sm font-medium text-gray-700">Local Government</label>
                    <!-- <select id="lga" name="lga" autocomplete="lga" @change="LGASelected"
                        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Local Government</option>
                        <option value="1">Dala</option>
                        <option value="2">Fagge</option>
                        <option value="3">Gwale</option>
                        <option value="4">Kano Municipal</option>
                        <option value="5">Nassarawa</option>
                        <option value="6">Tarauni</option>
                        <option value="7">Kumbotso</option>
                        <option value="8">Ungogo</option>
                    </select> -->
                    <el-select v-model="selectedLocalGovernment" placeholder="Select Local Government" size="large"
                        style="width: 240px;color:black;" class="text-black" @change="fetchWards">

                        <el-option v-for="item in LocalGovernments.data" :key="item.id" :label="item.name"
                            :value="item.id" />
                    </el-select>
                </div>
            </div>

            <br>

            <div v-if="selectedLGA">
                <div class="flex justify-around text-center">
                    <div class="">
                        <label for="lga" class="block text-sm font-medium text-gray-700">Ward</label>
                        <el-select v-model="selectedWard" placeholder="Select Ward" size="large"
                            style="width: 240px;color:black;" class="text-black" @change="fetchCommunities">

                            <el-option v-for="item in secondOptions" :key="item.id" :label="item.name"
                                :value="item.id" />
                        </el-select>
                    </div>
                </div>

                <br>
            </div>

            <div v-if="hasSelectedWard">
                <div class="flex justify-around text-center">
                    <div class="">
                        <label for="communities" class="block text-sm font-medium text-gray-700">Community</label>
                        <el-select v-model="selectedCommunity" placeholder="Select Community" size="large"
                            style="width: 240px;color:black;" class="text-black" @change="fetchBeneficiaries" >

                            <el-option v-for="item in thirdOptions" :key="item.id" :label="item.name"
                                :value="item.id" />
                        </el-select>
                    </div>

                   



                </div>
                <br>
            </div>

            <div v-if="hasSelectedWard">
                <div class="flex justify-around text-center">
                    <div class="">
                        <label for="numberInHousehold" class="block text-sm font-medium text-gray-700">How many benefiacries are in this household</label>
                        <el-input-number v-model="householdNum" :min="1" :max="20" />
                    </div>
                </div>
                <br>
            </div>

            <br>
         
            <!-- Next Button at bottom right corner -->
            <div class="flex justify-center">
                <!-- <el-button type="primary" :disabled="!selectedLGA" @click="previousStep" >Previous</el-button> -->

                <el-button type="primary" :disabled="!hasSelectedCommunity" @click="nextStep">Next</el-button>
            </div>
        </div>

        <div v-if="step === 2" >
            <el-card  v-for="x in householdNum" :key="x" class="m-4" >
                <template #header>
                  <div class="card-header">
                    <span>Beneficiary {{ x }}</span>
                  </div>
                </template>
                <p>Select Beneficiary
                <el-select
                    v-model="value"
                    filterable
                    placeholder="Select"
                    style="width: 240px"
                  >
                    <el-option
                      v-for="item in beneficiaries"
                      :key="item.id"
                      :label="`${item.first_name} ${item.surname || ''} ${item.other_name || ''}`.trim()"
                      :value="item.id"
                    />
                  </el-select>
                </p>

                <template #footer></template>
              </el-card>

              <div class="flex justify-center">
                <el-button type="primary" @click="previousStep" >Previous</el-button>

            </div>
        </div>

        <!-- <div v-if="selectedLGA">
            

            <br> -->

        <!-- Next Button at bottom right corner -->
        <!-- <div class="flex justify-center">
                <el-button type="primary" :disabled="!selectedWard" @click="previousStep">Previous</el-button>
                <el-button type="primary" :disabled="!selectedWard" @click="nextStep">Next</el-button>
            </div> -->
        <!-- </div> -->
    </div>
</template>
