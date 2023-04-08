/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';
import axios from 'axios';
import { useEffect, useRef, useState } from '@wordpress/element';
import Chart from 'chart.js';
import LoaderChart from '../../../../../theme/packages/components/loaders/LoaderChart';
import { Fragment } from 'react';
import StatsIcon from '../../../../../../images/icons/stats-up.svg';

const Analytics = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const [chart, setChart] = useState(false);
  const [chartDays, setChartDays] = useState('week');
  const [loadingChart, setLoadingChart] = useState(true);
  const chartEl = useRef(null);
  const chartLabels = useRef(null);
  let myChart = null;

  const questionIcon = () => {
    return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\n' +
      'width="18px" height="18px"' +
      '\t viewBox="0 0 100 100" style="enable-background:new 0 0 100 100; xml:space="preserve">\n' +
      '<g>\n' +
      '\t<path d="M51.1,23.1c-5.9-0.7-11.5,2.3-14.2,7.6c-0.7,1.4-0.2,3,1.2,3.7c1.4,0.7,3,0.2,3.7-1.2c1.7-3.2,5.1-5.1,8.7-4.7\n' +
      '\t\tc3.9,0.5,7.1,3.8,7.6,7.6c0.4,3.7-1.5,7.2-4.8,8.8c-4,1.9-6.5,6.1-6.5,10.7v11.7c0,1.5,1.2,2.8,2.8,2.8s2.8-1.2,2.8-2.8V55.6\n' +
      '\t\tc0-2.4,1.4-4.7,3.4-5.7c5.4-2.6,8.5-8.3,7.9-14.3C62.7,29.2,57.5,23.9,51.1,23.1z"/>\n' +
      '\t<path d="M49.4,74.9c-1.5,0-2.7,1.2-2.7,2.7s1.2,2.7,2.7,2.7c1.5,0,2.7-1.2,2.7-2.7S50.9,74.9,49.4,74.9z"/>\n' +
      '\t<path d="M49.3,2.1c-26,0-47.2,21.2-47.2,47.2c0,26,21.2,47.2,47.2,47.2c26,0,47.2-21.2,47.2-47.2C96.5,23.2,75.3,2.1,49.3,2.1z\n' +
      '\t\t M49.3,91c-23,0-41.7-18.7-41.7-41.7S26.3,7.6,49.3,7.6S91,26.3,91,49.3S72.3,91,49.3,91z"/>\n' +
      '</g>\n' +
      '</svg>';
  }

  const getStats = () => {
    setChart(false);
    setLoadingChart(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.get_stats;
    const formData = {
      user_id: business.business.ID,
      days: chartDays,
    }
    if (props.productId) {
      formData.product_id = props.productId;
    }
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data: formData,
    }).then(response => {
      if (response.data) {
        setChart(response.data);
        formatChart(response.data);
      }
      setLoadingChart(false);
    });
  }

  const formatChart = (chart) => {
    myChart = new Chart(chartEl.current, {
      type: 'line',
      data: {
        labels: Object.keys(chart[1]),
        datasets: [
          {
            label: lc_data.jst[298],
            data: chart && chart[1] && Object.values(chart[1]),
            backgroundColor: [
              'rgba(239, 78, 78, 0.1)',
            ],
            borderColor: [
              'rgba(239, 78, 78, 1)',
            ],
            borderWidth: 3,
          },
          {
            label: lc_data.jst[299],
            data: chart && chart[3] && Object.values(chart[3]),
            backgroundColor: [
              'rgba(249, 112, 61, 0.1)',
            ],
            borderColor: [
              'rgba(249, 112, 61, 1)',
            ],
            borderWidth: 3,
          },
          {
            label: lc_data.jst[300],
            data: chart && chart['leads'] && Object.values(chart['leads']),
            backgroundColor: [
              'rgba(62, 189, 147, 0.1)',
            ],
            borderColor: [
              'rgba(62, 189, 147, 1)',
            ],
            borderWidth: 3,
          },
          {
            label: lc_data.jst[289],
            data: chart && chart['conversions'] && Object.values(chart['conversions']),
            backgroundColor: [
              'rgba(247, 201, 72, 0.1)',
            ],
            borderColor: [
              'rgba(247, 201, 72, 1)',
            ],
            borderWidth: 3,
          },
        ],
      },
      options: {
        legend: {
          display: false,
        },
        legendCallback: (chart) => {
          const text = [];
          text.push('<ul class="flex flex-wrap items-center my-20 -mx-2">');
          for (let i = 0; i < chart.data.datasets.length; i++) {
            text.push(`<li class="label--handler relative flex flex-col mb-4 md:mb-0 px-2 w-full xs:w-1/2 md:w-1/4 rounded cursor-pointer"><div class="relative flex flex-col p-20" style="background-color:${chart.data.datasets[i].backgroundColor}">`);
            const label = chart.data.datasets[i].label;
            text.push(`<span class="-mb-2 font-light text-sm">${label}</span>`);
            if (lc_data.jst[300] === label || lc_data.jst[289] === label) {
              if (lc_data.jst[300] === label) {
                text.push(`<span class="font-bold text-6xl text-green-800">${chart.data.datasets[i].data.reduce((a, b) => parseFloat(a) + parseFloat(b), 0).toFixed(2)}%</span>`);
              } else if (lc_data.jst[289] === label) {
                text.push(`<span class="font-bold text-6xl text-yellow-800">${chart.data.datasets[i].data.reduce((a, b) => parseFloat(a) + parseFloat(b), 0).toFixed(2)}%</span>`);
              }
            } else {
              if (lc_data.jst[298] === label) {
                text.push(`<span class="font-bold text-6xl text-red-600">${chart.data.datasets[i].data.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0)}</span>`);
              } else if (lc_data.jst[299] === label) {
                text.push(`<span class="font-bold text-6xl text-orange-600">${chart.data.datasets[i].data.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0)}</span>`);
              }
            }
            if (lc_data.jst[298] === label) {
              text.push(`<span class="chart--tip impression absolute top-10 right-10 cursor-pointer">${questionIcon()}</span>`)
              text.push(`<span class="chart--tip-description absolute py-4 px-20 bg-grey-1100 rounded text-sm text-white">${lc_data.jst[290]}</span>`)
            } else if (lc_data.jst[299] === label) {
              text.push(`<span class="chart--tip reach absolute top-10 right-10 cursor-pointer">${questionIcon()}</span>`)
              text.push(`<span class="chart--tip-description absolute py-4 px-20 bg-grey-1100 rounded text-sm text-white">${lc_data.jst[291]}</span>`)
            } else if (lc_data.jst[300] === label) {
              text.push(`<span class="chart--tip lead absolute top-10 right-10 cursor-pointer">${questionIcon()}</span>`)
              text.push(`<span class="chart--tip-description absolute py-4 px-20 bg-grey-1100 rounded text-sm text-white">${lc_data.jst[292]}</span>`)
            } else if (lc_data.jst[289] === label) {
              text.push(`<span class="chart--tip conversion absolute top-10 right-10 cursor-pointer">${questionIcon()}</span>`)
              text.push(`<span class="chart--tip-description absolute py-4 px-20 bg-grey-1100 rounded text-sm text-white">${lc_data.jst[293]}</span>`)
            }
            text.push(`</div></li>`);
          }
          text.push('</ul>');
          return text.join("");
        },
      },
    });
    chartLabels.current.innerHTML = myChart.generateLegend();
    const label = document.querySelectorAll('.label--handler');
    if (label) {
      label.forEach((el, index) => {
        el.addEventListener('click', (e) => {
          const meta = myChart.getDatasetMeta(index);
          meta.hidden = meta.hidden === null ? !myChart.data.datasets[index].hidden : null;
          myChart.update();
        });
      });
    }
  }

  useEffect(() => {
    getStats();
  }, [chartDays]);

  return (
    <div className={`flex mt-30 w-full ${!props.productId ? 'xl:px-8' : ''}`}>
      <div className="statistics flex flex-col p-20 bg-white rounded shadow-theme w-full">

        {loadingChart && <LoaderChart/>}
        {!loadingChart &&
        <div className="flex justify-between items-center">
          <div className="flex-center">
            <ReactSVG src={`${lc_data.dir}dist/${StatsIcon}`}
                      className="relative mr-12 w-16 h-16 fill-grey-1000" style={{ top: '-2px' }}/>
            <span>{lc_data.jst[294]}</span>
          </div>

          {chart &&
          <div>
            <select
              className="flex py-6 px-10 bg-grey-100 rounded text-sm text-grey-900"
              onChange={(e) => setChartDays(e.target.value)}
              value={chartDays}
            >
              <option value="week">{lc_data.jst[295]}</option>
              <option value="month">{lc_data.jst[296]}</option>
              <option value="year">{lc_data.jst[297]}</option>
            </select>
          </div>
          }
        </div>
        }

        <div>
          {chart &&
          <div className={`${loadingChart ? 'hidden' : ''}`}>
            <div ref={chartLabels}></div>
            <canvas ref={chartEl} height="100" style={{ maxWidth: '100%' }}></canvas>
          </div>
          }
          {!chart && !loadingChart &&
          <div
            className="content flex items-center mt-20 leading-tight">{lc_data.jst[555]}</div>
          }
        </div>

      </div>
    </div>
  );
};

export default Analytics;
