<template>
  <a-card title="Thống kê" style="width: 100%">
    <div class="flex flex-row-reverse mb-4">
      <a-dropdown>
        <template #overlay>
          <a-menu>
            <a-menu-item key="1" @click="filterCharts('sevendays')">
              7 ngày qua (mặc định)
            </a-menu-item>
            <a-menu-item key="2" @click="filterCharts('month-week')">
              Tháng (theo tuần)
            </a-menu-item>
            <a-menu-item key="3" @click="filterCharts('month-day')">
              Tháng (theo ngày)
            </a-menu-item>
            <a-menu-item key="4" @click="filterCharts('quarter-week')">
              Quý (theo tuần)
            </a-menu-item>
            <a-menu-item key="5" @click="filterCharts('quarter-month')">
              Quý (theo tháng)
            </a-menu-item>
            <a-menu-item key="6" @click="filterCharts('year-month')">
              Năm (theo tháng)
            </a-menu-item>
            <!-- <a-menu-item key="7" @click="filterCharts('completed')">
              Năm (theo quý)
            </a-menu-item> -->
          </a-menu>
        </template>
        <a-button>
          Lọc thống kê theo<i class="ml-2 fa-solid fa-chevron-down"></i>
        </a-button>
      </a-dropdown>
      <a-dropdown class="mr-4">
        <template #overlay>
          <a-menu>
            <a-menu-item key="1" @click="changeCharts('ColumnChart')">
              Biểu đồ Cột (Column Chart)
            </a-menu-item>
            <a-menu-item key="2" @click="changeCharts('LineChart')">
              Biểu đồ Đường (Line Chart)
            </a-menu-item>
            <a-menu-item key="3" @click="changeCharts('PieChart')">
              Biểu đồ Bánh (Pie Chart)
            </a-menu-item>
            <a-menu-item key="4" @click="changeCharts('ScatterChart')">
              Biểu đồ Scatter (Scatter Chart)
            </a-menu-item>
            <a-menu-item key="5" @click="changeCharts('BarChart')">
              Biểu đồ Bar (Bar Chart)
            </a-menu-item>
            <a-menu-item key="6" @click="changeCharts('AreaChart')">
              Biểu đồ Area (Area Chart)
            </a-menu-item>
          </a-menu>
        </template>
        <a-button>
          Hiển thị thống kê theo<i class="ml-2 fa-solid fa-chevron-down"></i>
        </a-button>
      </a-dropdown>
    </div>
    <GChart :type="ChartStyle" :data="chartData" :options="chartOptions" />
  </a-card>
</template>

<script>
import { ref } from "vue";
import { GChart } from "vue-google-charts";
import { useMenu } from "../../../stores/use-menu";

export default {
  components: {
    GChart,
  },
  setup() {
    useMenu().onSelectedKeys(["admin-charts"]);
    const token = JSON.parse(localStorage.getItem("token"));
    const status = ref("");
    const chartData = ref({});
    const ChartStyle = ref('ColumnChart')
    const chartOptions = ref({
      chart: {
        title: "Company Performance",
        subtitle: "Sales, Expenses, and Profit: 2014-2017",
      },
    });

    async function getData() {
      try {
        const response = await axios.get(
          "http://127.0.0.1:8000/api/orders/charts?filter=" + status.value,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        const apiData = response.data;
        const chartDataArray = [["Thời gian", "Doanh thu"]];
        apiData.forEach((item) => {
          const datetime = item._id;
          const totalAmount = item.totalAmount;
          chartDataArray.push([datetime, totalAmount]);
        });
        chartData.value = chartDataArray;
      } catch (error) {
        console.error(error);
      }
    }

    function filterCharts(statusFilter) {
      status.value = statusFilter;
      getData();
    }

    function changeCharts(style) {
      ChartStyle.value = style;
    }

    getData();

    return {
      chartData,
      chartOptions,
      filterCharts,
      changeCharts,
      ChartStyle
    };
  },
};
</script>
