<template>
	<v-container grid-list-md text-xs-center>
		<v-layout row wrap>
			<v-flex xs12 sm6 offset-sm3>
				<h1 class="font-weight-light mb-20">Нужно заполнить форму для добавления операции</h1>
				<v-card>
					<v-flex xs12 md10 offset-md1>					
						<v-form class="operationform">
							<h2 class="font-weight-light toleft">Выберите тип операции:</h2>
							<v-radio-group v-model="type">
								<template v-for="operationitem in operationitems">
									<v-radio 
									class="title"
									color="orange"
									:label="operationitem.title" 
									:name="operationitem.slug"
									:value="operationitem.slug"
									></v-radio>
								</template>
							</v-radio-group>
							<div v-if="checkNote() == 'income'">
								<h2 class="font-weight-light toleft">Выберите категорию доходов:</h2>
								<v-radio-group v-model="selectedcat">
									<template v-for="operationcatin in operationcatins">
										<v-radio 
										class="title"
										color="orange"
										:label="operationcatin.title" 
										:name="operationcatin.slug"
										:value="operationcatin.id"
										></v-radio>
									</template>
								</v-radio-group>
							</div>
							<div v-if="checkNote() == 'costs'">
								<h2 class="font-weight-light toleft">Выберите категорию расходов:</h2>
								<v-radio-group v-model="selectedcat">
									<template v-for="operationcatcost in operationcatcosts">
										<v-radio 
										class="title"
										color="orange"
										:label="operationcatcost.title" 
										:name="operationcatcost.slug"
										:value="operationcatcost.id"
										></v-radio>
									</template>
								</v-radio-group>
							</div>
							<div v-if="checkCI() != ''">
								<h2 class="font-weight-light toleft">Выберите дату:</h2>
								<v-menu :close-on-content-click="false" v-model="menu" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
									<v-text-field slot="activator" v-model="computedDateFormatted" prepend-icon="event" readonly></v-text-field>
									<v-date-picker locale="ru-ru" v-model="date" @input="menu = false"></v-date-picker>
								</v-menu>
								<p>Date in ISO format: <strong>{{ date }}</strong></p>
								<h2 class="font-weight-light toleft">Напишите название:</h2>
								<v-text-field placeholder="Название элемента" name="elementname" v-model="title"></v-text-field>	
								<h2 class="font-weight-light toleft">Выберите валюту:</h2>
								<v-select :items="incomecurrency" item-text="type" item-value="value" v-model="defaultcurrency" >	
								</v-select>
								<h2 class="font-weight-light toleft">Выберите кошелек:</h2>
								<v-select :items="listwalletsarr" item-text="type" item-value="value" v-model="defaultwallet" >	
								</v-select>
								<div v-if="checkWal()">
									<h2 class="font-weight-light toleft">Сумма:</h2>
									<v-text-field placeholder="Введите сумму" name="summary" v-model="sum"></v-text-field>	
									<div>
										<v-btn color="warning" @click="submit">Отправить</v-btn>
									</div>		
								</div>						
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
				categories:{},	
				date: new Date().toISOString().substr(0, 10),
				title: '',
				sum:'',
				menu: false,						
				defaultwallet: {
					type: 'Выберите кошелек',
					value: false
				},
				listwallets: {},
				listwalletsarr: [],
				defaultcurrency: {
					type:'UAH',
					value:'UAH'
				},
				incomecurrency: [
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
				type: '',
				selectedcat: '',
				operationitems : [],
				operationcatins : [],
				operationcatcosts : []
			};
		},
		computed: {
			computedDateFormatted() {
				return this.formatDate(this.date)
			},
		},
		watch: {
			listwallets: function(val){
				this.updateListwallets();
			},
			categories: function(val){
				this.updateCategories();
			}
		},
		mounted: function() {
		var self = this;		
			axios.get('http://vue/backend/categories/list').then(response => {
				self.categories = response.data;
			});
			axios.get('http://vue/backend/safes/list').then(response => {
				self.listwallets = response.data;
			});
		},
		methods: {
			updateListwallets: function() {
				this.listwalletsarr = [];
				this.listwalletsarr.push(this.defaultwallet);
				for (var i in this.listwallets) {
					if(this.listwallets[i]){
						if(this.listwallets[i].title && this.listwallets[i].ccy){
							var wallet = {
								type:this.listwallets[i].title,
								value:this.listwallets[i].ccy,
							}
							this.listwalletsarr.push(wallet);
						}
					}
				}
			},
			updateCategories: function() {
				var cat = this.categories;
				this.operationitems = [];
				this.operationcatins = [];
				this.operationcatcosts = [];
				for (var i in cat) {
					if(typeof cat[i].parent != 'number'){
						var data = {
							id    : cat[i].ID,
							slug  : cat[i].slug,
							title : cat[i].title
						};
						this.operationitems.push(data);
					}
				}
				for (var i in cat) {
					if(cat[i].parent == 1){
						var data = {
							id    : cat[i].ID,
							slug  : cat[i].slug,
							title : cat[i].title
						};
						this.operationcatins.push(data);
					}
				}
				for (var i in cat) {
					if(cat[i].parent == 2){
						var data = {
							id    : cat[i].ID,
							slug  : cat[i].slug,
							title : cat[i].title
						};
						this.operationcatcosts.push(data);
					}
				}
				return cat;
			},
			submit () {
				var datasend = {};
				var validate = true;
				datasend.type     = this.type;
				datasend.title    = this.title;
				datasend.date     = this.date;
				datasend.category = this.selectedcat;
				datasend.sum = this.sum;
				datasend.ccy = this.defaultcurrency;

				if(validate === true){
					axios.get('http://vue/backend/income_costs/add', {
						params:datasend
					})
					.then(response => {		
						console.log(response.data);	
					})
				}
			},
			checkNote: function() {
				return this.type;
			},
			checkCI: function() {
				return this.selectedcat;
			},
			checkWal: function() {
				if(typeof this.defaultwallet == 'object'){
					return (this.defaultwallet.value != false);
				}else{
					return (this.defaultwallet != false);
				}
			},
			formatDate (date) {
				if (!date) return null
					const [year, month, day] = date.split('-')
				return `${day}.${month}.${year}`
			},
		}
	}
</script>

<style lang="css" scoped>
.mb-20 {
	margin-bottom: 20px;
}
h2 {
	font-size: 20px;
}
.operationform {
	margin-top: 20px;
}
.toleft {
	text-align: left;
}
</style>
