<template>
	<v-container grid-list-md>
		<v-layout row wrap>
			<v-flex xs12>				
				<v-card>
					<v-flex xs12 md10 offset-md1>					
						<v-form class="safeform">
								<h2 class="font-weight-light toleft">Название кошелька:</h2>
								<v-text-field placeholder="Название кошелька" name="elementname" v-model="title"></v-text-field>	
								<h2 class="font-weight-light toleft">Валюта кошелька:</h2>
								<v-select :items="incomecurrencysafe" item-text="type" item-value="value" v-model="defaultcurrencysafe" >	
								</v-select>
								<h2 class="font-weight-light toleft">Сумма:</h2>
								<v-text-field placeholder="Сумма на балансе" name="summary" v-model="sum"></v-text-field>	
								<div class="text-xs-center">
									<v-btn color="warning" @click="submit">Отправить</v-btn>
								</div>
						</v-form>
					</v-flex>
				</v-card>
			</v-flex>
		</v-layout>
	</v-container>
</template>

<script>	
	export default {
		data: function() {	
			return {
				title: '',
				sum:'',	
				defaultcurrencysafe: {
					type:'UAH',
					value:'UAH'
				},
				incomecurrencysafe: [
				{
					type:'UAH',
					value:'UAH'
				},
				{
					type:'USD',
					value:'USD'
				},
				{
					type:'RUR',
					value:'RUR'
				}
				],	
			};
		},
		methods: {
			submit () {
				var datasend = {};
				var validate = true;
				datasend.title    = this.title;
				datasend.money = this.sum;
				if(typeof(this.defaultcurrencysafe) == 'object') {
					datasend.ccy = this.defaultcurrencysafe.value;
				}
				else {
					datasend.ccy = this.defaultcurrencysafe;
				}
				if(validate === true){
					axios.get('http://vue/backend/safes/add', {
						params:datasend
					})
					.then(response => {		
						console.log(response.data);	
					})
				}
			},
		}
	}
</script>

<style lang="css" scoped>

</style>
