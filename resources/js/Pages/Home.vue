<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { onMounted, ref, reactive } from "vue";
import axios from "axios";

const wholeForm = reactive({
    formLocalGovernmentt: null,
    formWard: null,
    formCommunity: null,
    formHouseholdNum: null,
    formSelectedBeneficiary: null,
    formSightedBeneficiary: null,
    formBeneficiaryStatus: null,
    formNewLocation: null,
    formBeneficiaryHasNin: null,
    formBeneficiaryNin: null,
    formBeneficiaryTrackingId: null,
    formBeneficiaryPsrNumber: null,
    formBeneficiaryKschmaNumber: null,
    formSelectedFacility: null,
    formTrekkableInfo: null,
    formTransportFareInfo: null,
    formBeneficiaryWantToChangeFacility: null,
    formPrimaryReasonForFacilityChange: null,
    formBeneficiaryEeverAccessedServiceInAssignedPHC: null,
    formEducatedBeneficiaryAboutBhcpf: null,
});

const yesornoSelectBoxOptions = [
    { value: "Yes", label: "Yes" },
    { value: "No", label: "No" },
];

const beneficiaryStatusSelectBoxOptions = [
    { value: "Dead", label: "Dead" },
    { value: "Relocated Permanently", label: "Relocated Permanently" },
    { value: "Cannot be located", label: "Cannot be located" },
];

const trnasportFaresSelectBoxOptions = [
    { value: "200 - 500", label: "₦200 - ₦500" },
    { value: "501 - 1000", label: "₦501 - ₦1000" },
    { value: "1001 - 1500", label: "₦1001 - ₦1500" },
    { value: "1501 - 2000", label: "₦1501 - ₦2000" },
    { value: "Over 2001", label: "Over ₦2001" },
];

const selectedLGA = ref(false);
const hasSelectedWard = ref(false);
const hasSelectedCommunity = ref(false);
const selectedWard = ref("");
const selectedCommunity = ref("");
const step = ref(1);
const selectedLocalGovernment = ref("");
const householdNum = ref(1);
const selectedBeneficiaries = ref([]);

const sightedBeneficiary = ref([]);
const beneficiaryStatus = ref([]);
const newLocation = ref([]);
const beneficiaryHasNin = ref([]);
const beneficiaryNin = ref([]);
const beneficiaryTrackingId = ref([]);
const beneficiaryPsrNumber = ref([]);
const beneficiaryKschmaNumber = ref([]);
const selectedFacility = ref([]);
const trekkableInfo = ref([]);
const transportFareInfo = ref([]);
const beneficiaryWantToChangeFacility = ref([]);
const primaryReasonForFacilityChange = ref([]);
const beneficiaryEeverAccessedServiceInAssignedPHC = ref([]);
const educatedBeneficiaryAboutBhcpf = ref([]);

const nextStep = () => {
    step.value += 1;
    // Initialize selectedBeneficiaries array based on householdNum when advancing to step 2
    if (selectedBeneficiaries.value.length < householdNum.value) {
        selectedBeneficiaries.value = Array(householdNum.value).fill(null);
    }
};

const previousStep = () => {
    step.value -= 1;
};

const LGASelected = () => {
    selectedLGA.value = true;
};

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
const facilities = ref([]);

const fetchWards = () => {
    if (selectedLocalGovernment.value) {
        axios
            .get(`/fetchWards/${selectedLocalGovernment.value}`)
            .then((response) => {
                console.log("Options fetched:", response.data);
                secondOptions.value = response.data; // Assigning the fetched wards
                selectedLGA.value = true;
            })
            .catch((error) => {
                console.error("Error fetching options:", error);
            });
    } else {
        console.error("Local government is not selected");
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
};

const fetchCommunities = () => {
    if (selectedWard.value) {
        axios
            .get(`/fetchCommunites/${selectedWard.value}`)
            .then((response) => {
                console.log("Options fetched:", response.data);
                thirdOptions.value = response.data; // Assigning the fetched communities
                hasSelectedWard.value = true;
                //selectedWard.value = '';
            })
            .catch((error) => {
                console.error("Error fetching options:", error);
            });
    } else {
        console.error("Ward is not selected");
    }
};

const fetchBeneficiaries = () => {
    if (selectedWard.value) {
        axios
            .get(`/fetchBeneficiaries/${selectedWard.value}`)
            .then((response) => {
                console.log("Options fetched:", response.data);
                beneficiaries.value = response.data; // Assigning the fetched communities
                hasSelectedCommunity.value = true;
                //selectedWard.value = '';
            })
            .catch((error) => {
                console.error("Error fetching options:", error);
            });
    } else {
        console.error("Community is not selected");
    }

    if (selectedWard.value) {
        axios
            .get(`/fetchFacilities/${selectedWard.value}`)
            .then((response) => {
                console.log("Options fetched:", response.data);
                facilities.value = response.data; // Assigning the fetched communities
                // hasSelectedCommunity.value = true;
                //selectedWard.value = '';
            })
            .catch((error) => {
                console.error("Error fetching options:", error);
            });
    } else {
        console.error("Community is not selected");
    }
};

const fetchFacilities = () => {
    if (selectedWard.value) {
        axios
            .get(`/fetchFacilities/${selectedWard.value}`)
            .then((response) => {
                console.log("Options fetched:", response.data);
                beneficiaries.value = response.data; // Assigning the fetched communities
                // hasSelectedCommunity.value = true;
                //selectedWard.value = '';
            })
            .catch((error) => {
                console.error("Error fetching options:", error);
            });
    } else {
        console.error("Community is not selected");
    }
};

const totalHouseHold = ref(0);
const househholdID = ref(0);

const submitVerification = () => {
    axios
        .get(`/getTotalNumberOfAllHouseholds/`)
        .then((response) => {
            console.log("Total Household:", response.data);
            totalHouseHold.value = response.data; // Assigning the fetched wards

            //save household and get the id
            axios.post("/submitHousehold", {
                community_id: selectedCommunity.value,
                name: "Household " + (totalHouseHold.value + 1),
            })
                .then((response) => {
                    console.log("Household submitted:", response.data);
                    househholdID.value = response.data; // Assigning the fetched wards
                    for (let i = 0; i < householdNum.value; i++) {

                        //update beneficiary, to reflect the household, community and facility
                        axios.post("/updateBeneficiary", {
                            id: selectedBeneficiaries.value[i],
                            household_id: househholdID.value,
                            community_id: selectedCommunity.value,
                            facility_id: selectedFacility.value[i] === null ? 0 : selectedFacility.value[i],
                        })
                            .then((response) => {
                                console.log("Beneficiary updated:", response.data);
                            })
                            .catch((error) => {
                                console.error("Error updating beneficiary:", error);
                            });

                        axios
                            .post("/submitVerification", {
                                local_government: selectedLocalGovernment.value,
                                ward: selectedWard.value,
                                community: selectedCommunity.value,
                                selected_beneficiary: selectedBeneficiaries.value[i],
                                sighted_beneficiary: sightedBeneficiary.value[i] === "Yes" ? 1 : 0,
                            })
                            .then((response) => {
                                console.log("Verification submitted:", response.data);
                                router.visit("/finished");
                            })
                            .catch((error) => {
                                console.error("Error submitting verification:", error);
                            });
                    }
                })
                .catch((error) => {
                    console.error("Error submitting household:", error);
                });

        })
        .catch((error) => {
            console.error("Error fetching total household:", error);
        });

    // for each household beneficiary

};

</script>
<template>
    <div class="">
        <div class="mb-10">
            <h1 class="p-8 text-xl text-center">
                KANO STATE BASIC HEALTHCARE PROVISION FUND ENROLLEE VERIFICATION
                EXERCISE FORM
            </h1>
        </div>
        <br />

        <!-- Select Box for Local Government -->
        <div v-if="step === 1">
            <div class="flex justify-around text-center">
                <div class="">
                    <label for="lga" class="block text-sm font-medium text-gray-700">Local Government</label>
                    <el-select v-model="selectedLocalGovernment" placeholder="Select Local Government" size="large"
                        style="width: 240px; color: black" class="text-black" @change="fetchWards">
                        <el-option v-for="item in LocalGovernments.data" :key="item.id" :label="item.name"
                            :value="item.id" />
                    </el-select>
                </div>
            </div>

            <br />

            <div v-if="selectedLGA">
                <div class="flex justify-around text-center">
                    <div class="">
                        <label for="lga" class="block text-sm font-medium text-gray-700">Ward</label>
                        <el-select v-model="selectedWard" placeholder="Select Ward" size="large"
                            style="width: 240px; color: black" class="text-black" @change="fetchCommunities">
                            <el-option v-for="item in secondOptions" :key="item.id" :label="item.name"
                                :value="item.id" />
                        </el-select>
                    </div>
                </div>

                <br />
            </div>

            <div v-if="hasSelectedWard">
                <div class="flex justify-around text-center">
                    <div class="">
                        <label for="communities" class="block text-sm font-medium text-gray-700">Community</label>
                        <el-select v-model="selectedCommunity" placeholder="Select Community" size="large"
                            style="width: 240px; color: black" class="text-black" @change="fetchBeneficiaries">
                            <el-option v-for="item in thirdOptions" :key="item.id" :label="item.name"
                                :value="item.id" />
                        </el-select>
                    </div>
                </div>
                <br />
            </div>

            <div v-if="hasSelectedWard">
                <div class="flex justify-around text-center">
                    <div class="">
                        <label for="numberInHousehold" class="block text-sm font-medium text-gray-700">How many
                            benefiacries are in this household</label>
                        <el-input-number v-model="householdNum" :min="1" :max="20" />
                    </div>
                </div>
                <br />
            </div>

            <br />

            <!-- Next Button at bottom right corner -->
            <div class="flex justify-center">
                <!-- <el-button type="primary" :disabled="!selectedLGA" @click="previousStep" >Previous</el-button> -->

                <el-button type="primary" :disabled="!hasSelectedCommunity" @click="nextStep">Next</el-button>
            </div>
        </div>

        <div v-if="step === 2">
            <el-card v-for="(x, index) in householdNum" :key="x" class="m-4">
                <template #header>
                    <div class="card-header">
                        <span>Beneficiary {{ x }}</span>
                    </div>
                </template>

                <div class="flex justify-around text-center">
                    <div class="">
                        <label for="lga" class="block text-sm font-medium text-gray-700">Were you able to sight the
                            beneficiary?</label>
                        <el-select v-model="sightedBeneficiary[index]" placeholder="Please Select" size="large"
                            style="width: 240px; color: black" class="text-black">
                            <el-option v-for="item in yesornoSelectBoxOptions" :key="item.value" :label="item.label"
                                :value="item.value" />
                        </el-select>
                    </div>
                </div>

                <div v-if="sightedBeneficiary[index] === 'No'">
                    <br />
                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">If the beneficiary was not
                                found, what is
                                his/her status?
                            </label>
                            <el-select v-model="beneficiaryStatus[index]" placeholder="Please Select" size="large"
                                style="width: 240px; color: black" class="text-black">
                                <el-option v-for="item in beneficiaryStatusSelectBoxOptions" :key="item.value"
                                    :label="item.label" :value="item.value" />
                            </el-select>
                        </div>
                    </div>

                    <br />

                    <div v-if="beneficiaryStatus[index] === 'Relocated Permanently'">
                        <div class="flex justify-around text-center">
                            <div class="">
                                <label for="lga" class="block text-sm font-medium text-gray-700">If the beneficiary has
                                    relocated
                                    permanently, indicate the
                                    new location of the beneficiary</label>
                                <el-input placeholder="Please provide the new address of the beneficiary" size="large"
                                    style="width: 240px; color: black" v-model="newLocation[index]"
                                    class="text-black" />
                            </div>
                        </div>
                    </div>
                </div>

                <br />

                <div class="flex justify-around text-center">
                    <div class="">
                        <label for="lga" class="block text-sm font-medium text-gray-700">Select Beneficiary</label>
                        <el-select v-model="selectedBeneficiaries[index]" filterable placeholder="Select"
                            style="width: 240px">
                            <el-option v-for="item in beneficiaries" :key="item.id" :label="`${item.first_name} ${item.surname || ''} ${item.other_name || ''
                                    }`.trim()
                                " :value="item.id" />
                        </el-select>
                    </div>
                </div>

                <br />

                <div v-if="sightedBeneficiary[index] === 'Yes'">
                    <br />
                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">Did the beneficiary has NIN
                            </label>
                            <el-select v-model="beneficiaryHasNin[index]" placeholder="Please Select" size="large"
                                style="width: 240px; color: black" class="text-black">
                                <el-option v-for="item in yesornoSelectBoxOptions" :key="item.value" :label="item.label"
                                    :value="item.value" />
                            </el-select>
                        </div>
                    </div>

                    <br />

                    <div v-if="beneficiaryHasNin[index] === 'Yes'">
                        <div class="flex justify-around text-center">
                            <div class="">
                                <label for="lga" class="block text-sm font-medium text-gray-700">NIN Number</label>
                                <el-input placeholder="Please provide beneficiaries NIN Number" size="large"
                                    style="width: 240px; color: black" v-model="beneficiaryNin[index]"
                                    class="text-black" />
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="flex justify-around text-center" v-if="beneficiaryHasNin[index] === 'No'">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">Beneficiary Tracking ID
                                Number</label>
                            <el-input placeholder="Please provide beneficiaries tracking id number" size="large"
                                style="width: 240px; color: black" v-model="beneficiaryTrackingId[index]"
                                class="text-black" />
                        </div>
                    </div>

                    <br />

                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">NSR Number</label>
                            <el-input placeholder="Beneficiaries PSR number if available" size="large"
                                style="width: 240px; color: black" v-model="beneficiaryPsrNumber[index]"
                                class="text-black" />
                        </div>
                    </div>

                    <br />

                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">KSCHMA Number</label>
                            <el-input placeholder="Beneficiaries KSCHMA Number if avilable" size="large"
                                style="width: 240px; color: black" v-model="beneficiaryKschmaNumber[index]"
                                class="text-black" />
                        </div>
                    </div>

                    <br />

                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">Assigned Facility</label>
                            <el-select v-model="selectedFacility[index]"
                                placeholder="Select Beneficiaries Assigned Facility" size="large"
                                style="width: 240px; color: black" class="text-black">
                                <el-option v-for="item in facilities" :key="item.id" :label="item.name"
                                    :value="item.id" />
                            </el-select>
                        </div>
                    </div>

                    <br />

                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">Is the Distance to Assigned
                                PHC Trekkable
                            </label>
                            <el-select v-model="trekkableInfo[index]" placeholder="Please Select" size="large"
                                style="width: 240px; color: black" class="text-black">
                                <el-option v-for="item in yesornoSelectBoxOptions" :key="item.value" :label="item.label"
                                    :value="item.value" />
                            </el-select>
                        </div>
                    </div>

                    <div v-if="trekkableInfo[index] === 'No'">
                        <br />
                        <div class="flex justify-around text-center">
                            <div class="">
                                <label for="lga" class="block text-sm font-medium text-gray-700">Transport Fare to
                                    Assigned
                                    Facility</label>
                                <el-select v-model="transportFareInfo[index]" placeholder="Please Select" size="large"
                                    style="width: 240px; color: black" class="text-black">
                                    <el-option v-for="item in trnasportFaresSelectBoxOptions" :key="item.value"
                                        :label="item.label" :value="item.value" />
                                </el-select>
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">Does the beneficiary want
                                to change their
                                assigned PHC
                            </label>
                            <el-select v-model="beneficiaryWantToChangeFacility[index]" placeholder="Please Select"
                                size="large" style="width: 240px; color: black" class="text-black">
                                <el-option v-for="item in yesornoSelectBoxOptions" :key="item.value" :label="item.label"
                                    :value="item.value" />
                            </el-select>
                        </div>
                    </div>

                    <div v-if="beneficiaryWantToChangeFacility[index] === 'Yes'">
                        <br />
                        <div class="flex justify-around text-center">
                            <div class="">
                                <label for="lga" class="block text-sm font-medium text-gray-700">What is the primary
                                    reason the
                                    Beneficiary want to change
                                    their assigned facility</label>
                                <el-input placeholder="Please Enter The Reason" size="large"
                                    style="width: 240px; color: black" v-model="primaryReasonForFacilityChange[index]"
                                    class="text-black" />
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">Does the beneficiary ever
                                accessed
                                healthcare service at
                                assigned phc
                            </label>
                            <el-select v-model="beneficiaryEeverAccessedServiceInAssignedPHC[index]"
                                placeholder="Please Select" size="large" style="width: 240px; color: black"
                                class="text-black">
                                <el-option v-for="item in yesornoSelectBoxOptions" :key="item.value" :label="item.label"
                                    :value="item.value" />
                            </el-select>
                        </div>
                    </div>

                    <br />

                    <div class="flex justify-around text-center">
                        <div class="">
                            <label for="lga" class="block text-sm font-medium text-gray-700">Did you educate the
                                beneficiary about BHCPF
                                during the exercise
                            </label>
                            <el-select v-model="educatedBeneficiaryAboutBhcpf[index]" placeholder="Please Select"
                                size="large" style="width: 240px; color: black" class="text-black">
                                <el-option v-for="item in yesornoSelectBoxOptions" :key="item.value" :label="item.label"
                                    :value="item.value" />
                            </el-select>
                        </div>
                    </div>
                </div>

                <template #footer></template>
            </el-card>

            <div class="flex justify-center">
                <el-button type="primary" @click="previousStep">Previous</el-button>

                <!-- Submit Botton and Float Button To Right -->
                <el-button type="primary" @click="submitVerification">Save</el-button>

                <br />
            </div>
            <br />
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
