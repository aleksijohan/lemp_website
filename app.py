import streamlit as st
import pandas as pd
import mysql.connector

# ─────────────── ULKOASU ───────────────
st.set_page_config(
    page_title="Helsinki Live Dashboard",
    layout="wide",
    initial_sidebar_state="collapsed"
)

st.markdown("""
<style>
    .big-font {font-size:50px !important; font-weight: bold;}
    .temp {color: #00BFFF;}
    .crypto {color: #FFD700;}
    .block-container {padding-top: 2rem;}
</style>
""", unsafe_allow_html=True)

st.markdown("<h1 style='text-align: center; color: white;'>Helsinki Live Dashboard</h1>", unsafe_allow_html=True)
st.markdown("<p style='text-align: center; color: #888; font-size: 1.2rem;'>Päivittyy automaattisesti 15 min välein • Open-Meteo + CoinGecko</p>", unsafe_allow_html=True)

try:
    conn = mysql.connector.connect(
        host="localhost",
        user="clock_user",
        password="helppo123",
        database="clock_db"        # <-- tämä riittää, ei tarvita USE-komentoa
    )
    query = """
        SELECT measured_at AS aika, 
               temperature AS lämpötila, 
               COALESCE(xrp_price, 0) AS xrp_price
        FROM weather 
        ORDER BY measured_at DESC 
        LIMIT 48
    """
    df = pd.read_sql(query, conn)
    conn.close()

    latest = df.iloc[0]  

    col1, col2, col3 = st.columns([2,1,2])
    with col1:
        st.markdown(f"<p class='temp big-font'>{latest['lämpötila']:.1f} °C</p>", unsafe_allow_html=True)
        st.markdown("<p style='text-align: center; color: #aaa; font-size: 1.3rem;'>Helsingin lämpötila</p>", unsafe_allow_html=True)
    with col2:
        st.markdown("<p style='text-align: center; font-size: 50px;'>Thermometer</p>", unsafe_allow_html=True)
    with col3:
        xrp = latest['xrp_price'] if latest['xrp_price'] else 0
        st.markdown(f"<p class='crypto big-font'>${xrp:.4f}</p>", unsafe_allow_html=True)
        st.markdown("<p style='text-align: center; color: #aaa; font-size: 1.3rem;'>XRP / USD</p>", unsafe_allow_html=True)

    st.markdown("---")

    col1, col2 = st.columns(2)
    with col1:
        st.subheader("Lämpötilan kehitys")
        st.line_chart(df.set_index('aika')['lämpötila'], use_container_width=True)
    with col2:
        st.subheader("XRP/USD kurssin kehitys")
        st.line_chart(df.set_index('aika')['xrp_price'], use_container_width=True)

    # Viimeisin päivitys
    st.success(f"Viimeisin päivitys: {latest['aika'].strftime('%d.%m.%Y %H:%M')}")

    # Paluulinkki
    st.markdown("← [Takaisin pääsivulle](/)", unsafe_allow_html=True)

except Exception as e:
    st.error(f"Yhteysvirhe: {e}")
