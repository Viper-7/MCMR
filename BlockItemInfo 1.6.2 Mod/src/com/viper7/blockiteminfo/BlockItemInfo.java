package com.viper7.blockiteminfo;

import java.io.File;
import java.io.IOException;
import java.util.logging.Level;

import net.minecraft.block.Block;
import net.minecraft.item.Item;

import com.google.common.base.Charsets;
import com.google.common.base.Joiner;
import com.google.common.base.Joiner.MapJoiner;
import com.google.common.collect.ImmutableListMultimap;
import com.google.common.io.Files;

import cpw.mods.fml.common.FMLLog;
import cpw.mods.fml.common.Mod;
import cpw.mods.fml.common.Mod.EventHandler;
import cpw.mods.fml.common.event.FMLPostInitializationEvent;
import cpw.mods.fml.common.network.NetworkMod;
import cpw.mods.fml.relauncher.FMLInjectionData;

@Mod( modid = "BlockItemInfo", name = "Block/Item Info Gatherer", version = "1.0" )
@NetworkMod( clientSideRequired=true, serverSideRequired=true )

public class BlockItemInfo {
	@EventHandler
	public void postInit(FMLPostInitializationEvent event) {
		File minecraftDir = (File)(FMLInjectionData.data()[6]);
		
        ImmutableListMultimap.Builder<String, String> builder = ImmutableListMultimap.builder();
        for(int i=0;i<4096;i++) {
        	if(Block.blocksList[i] != null) {
        		builder.put(String.valueOf(i), Block.blocksList[i].getUnlocalizedName());
        	}
        }
        
        File f = new File(minecraftDir, "blocksList.csv");
        MapJoiner mapJoiner = Joiner.on("\n").withKeyValueSeparator(",");
        try
        {
            Files.write(mapJoiner.join(builder.build().entries()), f, Charsets.UTF_8);
            FMLLog.log(Level.INFO, "Dumped block list data to %s", f.getAbsolutePath());
        }
        catch (IOException e)
        {
            FMLLog.log(Level.SEVERE, e, "Failed to write block list data to %s", f.getAbsolutePath());
        }
        
        builder = ImmutableListMultimap.builder();
        for(int i=256;i<32000;i++) {
        	if(Item.itemsList[i] != null) {
        		builder.putAll(String.valueOf(i - 256), Item.itemsList[i].getUnlocalizedName());
        	}
        }
        
        f = new File(minecraftDir, "itemsList.csv");
        mapJoiner = Joiner.on("\n").withKeyValueSeparator(",");
        try
        {
            Files.write(mapJoiner.join(builder.build().entries()), f, Charsets.UTF_8);
            FMLLog.log(Level.INFO, "Dumped item list data to %s", f.getAbsolutePath());
        }
        catch (IOException e)
        {
            FMLLog.log(Level.SEVERE, e, "Failed to write item list data to %s", f.getAbsolutePath());
        }
        
        System.exit(-1);
	}
}
