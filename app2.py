import streamlit as st
import pandas as pd
import mysql.connector
import pytz
from datetime import datetime
import altair as alt

st.set_page_config(page_title="Data-analyysi", layout="wide")

tz_helsinki = pytz.timezone('Europe/Helsinki')

@st.cache_data(ttl=60)  # Päivitä 1 min välein
def load_data():
    conn = mysql.connector.connect(
        host="localhost", user="example_user",
        password="12345", database="example_db"
    )
    query = "SELECT * FROM crypto_weather ORDER BY timestamp DESC LIMIT 50"
    df = pd.read_sql(query, conn)
    conn.close()
    return df

st.title("XRP-kurssi & Helsingin sää")

df = load_data()

if df.empty:
    st.warning("Ei dataa – odota cron-ajoa.")
else:
    df['helsinki_time'] = pd.to_datetime(df['timestamp']).dt.tz_localize('UTC').dt.tz_convert(tz_helsinki)
    
    latest = df.iloc[0]
    
    col1, col2, col3 = st.columns(3)
    with col1:
        st.metric("XRP-hinta (USD)", f"${latest['xrp_price_usd']:.4f}")
    with col2:
        st.metric("Lämpötila", f"{latest['helsinki_temp_c']}°C")
    with col3:
        st.metric("Säätila", latest['helsinki_weather'])
    
    # Tarkempi XRP-kaavio Altair:lla (automaattinen Y-skaalaus + zoom)
    st.subheader("XRP-kurssin kehitys (interaktiivinen)")
    xrp_chart = alt.Chart(df).mark_line(point=True).encode(
        x=alt.X('helsinki_time:T', title="Aika"),
        y=alt.Y('xrp_price_usd:Q', title="Hinta (USD)", scale=alt.Scale(domain=[df['xrp_price_usd'].min() - 0.01, df['xrp_price_usd'].max() + 0.01])),  # Tarkka skaalaus
        tooltip=['helsinki_time', 'xrp_price_usd']
    ).interactive().properties(width='container', height=300)
    st.altair_chart(xrp_chart, use_container_width=True)
    
    st.subheader("Helsingin lämpötilan kehitys")
    st.line_chart(df.set_index('helsinki_time')['helsinki_temp_c'])
    
    with st.expander("Raakadata (viimeiset 10)"):
        st.dataframe(df[['helsinki_time', 'xrp_price_usd', 'helsinki_temp_c', 'helsinki_weather']].head(10))

st.caption(f"Viimeisin päivitys: {datetime.now(tz_helsinki).strftime('%d.%m.%Y %H:%M:%S')} (cron 15 min välein)")
