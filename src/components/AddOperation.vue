<template>
	<v-container grid-list-md text-xs-center>
		<v-layout row wrap>
			<v-flex xs12 sm6 offset-sm3>
				<h3 class="font-weight-light">Курс валют:</h3>
				<h1 class="font-weight-light mb-20">Нужно заполнить форму для добавления операции</h1>				
				<v-card>
					<v-flex xs12 md10 offset-md1>					
						<v-form class="operationform">
							<h2 class="font-weight-light toleft">Выберите тип операции:</h2>
							<v-radio-group v-model="selected">
								<template v-for="operationitem in operationitems">
									<v-radio 
									class="title"
									color="orange"
									:label="operationitem.name" 
									:name="operationitem.value"
									:value="operationitem.value"
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
										:label="operationcatin.name" 
										:name="operationcatin.value"
										:value="operationcatin.value"
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
										:label="operationcatcost.name" 
										:name="operationcatcost.value"
										:value="operationcatcost.value"
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
								<v-text-field placeholder="Название элемента" name="elementname" ></v-text-field>								
								<h2 class="font-weight-light toleft">Выберите валюту:</h2>
								<v-select :items="incomecurrency" item-text="type" item-value="value" v-model="defaultcurrency">				
								</v-select>
								<h2 class="font-weight-light toleft">Сумма:</h2>
								<v-text-field placeholder="Введите сумму" name="summary" ></v-text-field>	
								<div>
									<v-btn color="warning" type="submit">Отправить</v-btn>
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
		name: 'AddPayment',
		data: function() {	
			return {
				currencies:{},
				currencieslist:new Array(),
				date: new Date().toISOString().substr(0, 10),
				menu: false,
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
				selected: '',
				selectedcat: '',
				selectedcurr: '',
				operationitems : [],
				operationcatins : [],
				operationcatcosts : []
			};
		},
		computed: {
			computedDateFormatted () {
				return this.formatDate(this.date)
			}
		},
		created() {
			axios.get('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5')
			.then(response => {
			// JSON responses are automatically parsed.    
			for (var k in response.data) {
				var smdata = response.data[k];				
				this.currencies[smdata.ccy] = {};
				this.currencies[smdata.ccy].ccy  = smdata.ccy;
				this.currencies[smdata.ccy].buy  = smdata.buy;
				this.currencies[smdata.ccy].sale = smdata.sale;
				var currencies = this.currencies;
				this.currencieslist.push(this.currencies[smdata.ccy]);
				// =25/0.39500
				// if(smdata.ccy)
				// currency = response.data[k]
			}			
		})
			.catch(e => {
				this.errors.push(e)
			})
		},
		mounted: function() {
			var self = this;
			setTimeout(function(self) {
				self.operationitems = [{
					name: 'Доходы',
					value: 'income'
				}, {
					name: 'Расходы',
					value: 'costs'
				}, ];
				self.operationcatins = [{
					name: 'Зарплата',
					value: 'salary'
				}, {
					name: 'Халтура',
					value: 'quickmoney'
				}, {
					name: 'Подарок',
					value: 'gift'
				}];
				self.operationcatcosts = [{
					name: 'Покупка продуктов',
					value: 'products'
				}, {
					name: 'Транспортные расходы',
					value: 'transport'
				}, {
					name: 'Подарки',
					value: 'giftcost'
				}];
			}, 0, self);
		},
		methods: {
			checkNote: function() {
				return this.selected;
			},
			checkCI: function() {
				return this.selectedcat;
			},
			formatDate (date) {
				if (!date) return null
					const [year, month, day] = date.split('-')
				return `${day}.${month}.${year}`
			}
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
