<script setup>
import { ref } from 'vue';  

const selectedLGA = ref(false);
const selectedWard = ref(false);
const selectedCommunity = ref(false);
const step = ref(1);

const nextStep = () => {
    step.value += 1;
}

const previousStep = () => {
    step.value -= 1;
}

const LGASelected = () => {
    selectedLGA.value = true;
}

</script>
<template>
    <div class="">
        <div class="mb-10">
            <h1 class="p-8 text-xl text-center">KANO STATE BASIC HEALTHCARE PROVISION FUND ENROLLEE VERIFICATION EXERCISE FORM</h1>
        </div>
        <br>
        <br>
        <br>
        <br>
        <!-- Select Box for Local Government -->
         <div v-if="step === 1" >
            <div class="flex justify-around text-center" >
                <div class="">
                    <label for="lga" class="block text-sm font-medium text-gray-700">Local Government</label>
                    <select id="lga" name="lga" autocomplete="lga" @change="LGASelected" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Local Government</option>
                        <option value="1">Dala</option>
                        <option value="2">Fagge</option>
                        <option value="3">Gwale</option>
                        <option value="4">Kano Municipal</option>
                        <option value="5">Nassarawa</option>
                        <option value="6">Tarauni</option>
                        <option value="7">Kumbotso</option>
                        <option value="8">Ungogo</option>
                    </select>
                </div>
            </div>
    
            <br>
    
            <!-- Next Button at bottom right corner -->
            <div class="flex justify-center">
                <!-- <el-button type="primary" :disabled="!selectedLGA" @click="previousStep" >Previous</el-button> -->
                <el-button type="primary" :disabled="!selectedLGA"  @click="nextStep" >Next</el-button>
            </div>
         </div>

         <div v-if="step === 2" >
            <div class="flex justify-around text-center" >
                <div class="">
                    <label for="lga" class="block text-sm font-medium text-gray-700">Ward</label>
                    <select id="lga" name="lga" autocomplete="lga" @change="wardSelected" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Ward</option>
                        <option value="1">Dala</option>
                        <option value="2">Gwammaja</option>
                        <option value="3">Adakawa</option>
                        <option value="4">Kofar Mazugal</option>
                        <option value="5">Kabuwaya</option>
                        <option value="6">Bakin Ruwa</option>
                        <option value="7">Gobirawa</option>
                        <option value="8">Yalwa</option>
                    </select>
                </div>
            </div>
    
            <br>
    
            <!-- Next Button at bottom right corner -->
            <div class="flex justify-center">
                <el-button type="primary" :disabled="!selectedWard" @click="previousStep" >Previous</el-button>
                <el-button type="primary" :disabled="!selectedWard" @click="nextStep" >Next</el-button>
            </div>
         </div>

         <div v-if="step === 3" >
            <div class="flex justify-around text-center" >
                <div class="">
                    <label for="lga" class="block text-sm font-medium text-gray-700">Local Government</label>
                    <select id="lga" name="lga" autocomplete="lga" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Local Government</option>
                        <option value="1">Dala</option>
                        <option value="2">Fagge</option>
                        <option value="3">Gwale</option>
                        <option value="4">Kano Municipal</option>
                        <option value="5">Nassarawa</option>
                        <option value="6">Tarauni</option>
                        <option value="7">Kumbotso</option>
                        <option value="8">Ungogo</option>
                    </select>
                </div>
            </div>
    
            <br>
    
            <!-- Next Button at bottom right corner -->
            <div class="flex justify-center">
                <el-button type="primary" :disabled="!selectedCommunity"  >Primary</el-button>
            </div>
         </div>
    </div>
</template>
